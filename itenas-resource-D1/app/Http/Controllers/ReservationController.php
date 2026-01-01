<?php

namespace App\Http\Controllers;

use App\Events\NewReservationEvent;
use App\Http\Requests\StoreReservationRequest;
use App\Jobs\SendReservationEmailJob;
use App\Exports\ReservationsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Asset;
use App\Models\User;
use App\Notifications\NewReservationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * 1. RIWAYAT PEMINJAMAN ASET (BARANG)
     * Mahasiswa hanya melihat miliknya sendiri.
     */
    public function indexAssets()
    {
        $user = Auth::user();
        
        $reservations = Reservation::with(['reservationItems.asset', 'lab'])
            ->where('type', 'asset')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('reservations.index_assets', compact('reservations'));
    }

    /**
     * 2. RIWAYAT BOOKING RUANGAN (LAB)
     */
    public function indexRooms()
    {
        $user = Auth::user();
        
        $reservations = Reservation::with(['lab.prodi'])
            ->where('type', 'room')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('reservations.index_rooms', compact('reservations'));
    }

    /**
     * 3. FORM PINJAM ASET
     */
    public function create($asset_id)
    {
        $asset = Asset::with('lab.prodi')->findOrFail($asset_id);
        
        // [FIX] Mengambil aset lain yang tersedia (Gunakan 'quantity' bukan 'stock')
        $allAssets = Asset::where('status', 'available')
            ->where('quantity', '>', 0) // <--- PERBAIKAN DI SINI
            ->get(); 
        
        return view('reservations.create', compact('asset', 'allAssets'));
    }

    /**
     * 4. PROSES SIMPAN & VALIDASI
     */
    public function store(Request $request) 
    {
        // A. Validasi Input Dasar
        $request->validate([
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'items' => 'required|array|min:1',
            'items.*.asset_id' => 'required|exists:assets,id',
            'items.*.quantity' => 'required|integer|min:1',
            'proposal_file' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'purpose' => 'required|string|max:500',
        ]);

        // B. LOGIKA VALIDASI MAKSIMAL 7 HARI
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $diffInDays = $start->diffInDays($end);

        if ($diffInDays > 7) {
            return back()->withErrors(['error' => "Batas waktu maksimal peminjaman adalah 7 hari. Anda mencoba meminjam selama $diffInDays hari."])->withInput();
        }

        // C. Cek Stok Aset sebelum simpan
        foreach ($request->items as $item) {
            $asset = Asset::findOrFail($item['asset_id']);
            
            // [FIX] Perbaikan nama kolom 'stock' menjadi 'quantity'
            if ($item['quantity'] > $asset->quantity) { // <--- PERBAIKAN DI SINI
                return back()->withErrors(['error' => "Stok aset '{$asset->name}' tidak mencukupi! Sisa stok saat ini: {$asset->quantity}"])->withInput();
            }
        }

        // D. Upload File Proposal (Jika ada)
        $filePath = null;
        if ($request->hasFile('proposal_file')) {
            $filePath = $request->file('proposal_file')->store('proposals', 'public');
        }

        // E. Generate Kode Transaksi Unik
        $prodiCode = (Auth::user()->prodi) ? Auth::user()->prodi->code : 'MHS';
        $trxCode = 'TRX-' . $prodiCode . '-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        
        // F. Simpan Header Reservasi
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'transaction_code' => $trxCode,
            'type' => 'asset', 
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
            'status' => 'pending',
            'proposal_file' => $filePath,
        ]);

        // G. Simpan Detail Item & Kumpulkan ID Prodi untuk Notifikasi
        $targetProdiIds = [];
        foreach ($request->items as $item) {
            $dbAsset = Asset::find($item['asset_id']);
            
            ReservationItem::create([
                'reservation_id' => $reservation->id,
                'asset_id' => $item['asset_id'],
                'quantity' => $item['quantity'],
                'note' => $request->purpose
            ]);

            // Ambil prodi_id dari Lab tempat aset berada
            if ($dbAsset->lab && $dbAsset->lab->prodi_id) {
                $targetProdiIds[] = $dbAsset->lab->prodi_id;
            }
        }

        // H. SISTEM NOTIFIKASI KE LABORAN TERKAIT
        try {
            $targetProdiIds = array_unique($targetProdiIds);
            
            // Cari laboran yang mengelola prodi tempat barang tersebut berada
            $laborans = User::role('Laboran')
                ->whereIn('prodi_id', $targetProdiIds)
                ->get();

            if ($laborans->isNotEmpty()) {
                Notification::send($laborans, new NewReservationNotification($reservation));
            }
            
            // Trigger Event untuk real-time (Pusher) dan Email
            event(new NewReservationEvent($reservation));
            SendReservationEmailJob::dispatch($reservation);
            
        } catch (\Exception $e) {
            // Notifikasi gagal tidak membatalkan transaksi (silent fail)
            \Log::warning("Peminjaman berhasil tapi notifikasi gagal: " . $e->getMessage());
        }

        return redirect()->route('reservations.assets')
            ->with('success', 'Peminjaman berhasil diajukan! Silakan tunggu konfirmasi dari Laboran prodi terkait. Kode: ' . $trxCode);
    }

    /**
     * 5. TIKET QR CODE
     */
    public function downloadTicket($id)
    {
        $reservation = Reservation::with(['user', 'reservationItems.asset.lab', 'lab'])->findOrFail($id);

        // Keamanan: Hanya pemilik atau admin/laboran yang boleh melihat tiket
        if (Auth::user()->id != $reservation->user_id && !Auth::user()->hasRole(['Superadmin', 'Laboran'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk tiket ini.');
        }

        return view('reservations.ticket', compact('reservation'));
    }
}