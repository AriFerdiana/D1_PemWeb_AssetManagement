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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * 1. HISTORY PEMINJAMAN ASET (BARANG)
     */
    public function indexAssets()
    {
        $user = Auth::user();
        $query = Reservation::with(['user.prodi', 'reservationItems.asset'])
            ->where('type', 'asset');

        if (!$user->hasRole(['Superadmin', 'Laboran', 'Dosen'])) {
            $query->where('user_id', $user->id);
        }

        $reservations = $query->latest()->paginate(10);
        return view('reservations.index_assets', compact('reservations'));
    }

    /**
     * 2. HISTORY BOOKING RUANGAN (LAB)
     */
    public function indexRooms()
    {
        $user = Auth::user();
        $query = Reservation::with(['user.prodi', 'lab'])
            ->where('type', 'room');

        if (!$user->hasRole(['Superadmin', 'Laboran', 'Dosen'])) {
            $query->where('user_id', $user->id);
        }

        $reservations = $query->latest()->paginate(10);
        return view('reservations.index_rooms', compact('reservations'));
    }

    /**
     * 3. FORM PINJAM ASET
     */
    public function create($asset_id)
    {
        // Ambil data aset awal untuk pre-select di baris pertama
        $asset = Asset::with('lab.prodi')->findOrFail($asset_id);
        
        // Ambil semua daftar aset agar bisa dipilih di baris tambahan (Dynamic)
        $allAssets = Asset::all(); 
        
        return view('reservations.create', compact('asset', 'allAssets'));
    }

    /**
     * 4. PROSES SIMPAN MULTI-ASET (Logic Dynamic Form)
     */
    public function store(Request $request) 
    {
        // A. Validasi (Menggunakan array validation)
        $request->validate([
            'start_time' => 'required|after:now',
            'end_time' => 'required|after:start_time',
            'items' => 'required|array|min:1',
            'items.*.asset_id' => 'required|exists:assets,id',
            'items.*.quantity' => 'required|integer|min:1',
            'proposal_file' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'purpose' => 'required|string|max:500',
        ]);

        // B. Cek Stok & Jadwal untuk SEMUA barang yang dipilih
        foreach ($request->items as $item) {
            $asset = Asset::findOrFail($item['asset_id']);
            
            // Cek Stok
            if ($item['quantity'] > $asset->stock) {
                return back()->withErrors(['error' => "Stok {$asset->name} tidak mencukupi!"]);
            }

            // Cek Bentrok Jadwal
            $isBooked = Reservation::whereHas('reservationItems', function($q) use ($item) {
                    $q->where('asset_id', $item['asset_id']);
                })
                ->whereIn('status', ['approved', 'borrowed'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                          ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
                })
                ->exists();

            if ($isBooked) {
                return back()->withErrors(['error' => "Aset {$asset->name} sudah dibooking orang lain di jam tersebut!"]);
            }
        }

        // C. Upload File
        $filePath = null;
        if ($request->hasFile('proposal_file')) {
            $filePath = $request->file('proposal_file')->store('proposals', 'public');
        }

        // D. Generate Kode Unik
        $prodiCode = Auth::user()->prodi ? Auth::user()->prodi->code : 'UMUM';
        $trxCode = 'TRX-' . $prodiCode . '-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        
        // E. Simpan Header Reservasi
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

        // F. Simpan Detail Item (Looping melalui array items)
        foreach ($request->items as $item) {
            ReservationItem::create([
                'reservation_id' => $reservation->id,
                'asset_id' => $item['asset_id'],
                'quantity' => $item['quantity'],
                'note' => $request->purpose
            ]);
        }

        // G. REAL-TIME NOTIFICATION (PUSHER)
        event(new NewReservationEvent($reservation));

        // H. Kirim Email (Queue)
        try {
            SendReservationEmailJob::dispatch($reservation);
        } catch (\Exception $e) { }

        return redirect()->route('reservations.assets')->with('success', 'Peminjaman beberapa aset berhasil diajukan!');
    }

    /**
     * 5. UPDATE STATUS (Approve/Reject/Return)
     */
    public function updateStatus(Request $request, $id)
    {
        if (!Auth::user()->hasRole(['Superadmin', 'Laboran', 'Dosen'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $reservation = Reservation::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:approved,rejected,returned',
            'note' => 'nullable|string'
        ]);

        $reservation->update([
            'status' => $request->status,
            'rejection_note' => $request->status == 'rejected' ? $request->note : null
        ]);
        
        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    /**
     * 6. EXPORT EXCEL
     */
    public function export()
    {
        return Excel::download(new ReservationsExport, 'laporan_peminjaman_itenas.xlsx');
    }

    /**
     * 7. TIKET QR CODE
     */
    public function downloadTicket($id)
    {
        $reservation = Reservation::with(['user', 'reservationItems.asset', 'lab'])->findOrFail($id);

        if (Auth::user()->id != $reservation->user_id && !Auth::user()->hasRole(['Superadmin', 'Laboran'])) {
            abort(403);
        }

        if ($reservation->status != 'approved') {
            return back()->with('error', 'Tiket belum tersedia.');
        }

        return view('reservations.ticket', compact('reservation'));
    }
}