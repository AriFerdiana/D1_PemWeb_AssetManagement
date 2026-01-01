<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminReservationController extends Controller
{
    /**
     * TAMPILAN DAFTAR TRANSAKSI
     * Filter ketat memisahkan tipe aset dan ruangan agar tidak bocor antar prodi.
     */
    public function index(Request $request)
{
    $user = Auth::user();
    // Eager loading sangat penting agar query filter prodi bisa berjalan cepat
    $query = Reservation::with(['user', 'reservationItems.asset.lab', 'lab']);

    if ($user->hasRole('Laboran')) {
        $prodiId = $user->prodi_id;

        // KUNCI PERBAIKAN: Menggunakan scope where yang membungkus seluruh kondisi OR
        $query->where(function($q) use ($prodiId) {
            // Jalur 1: Khusus untuk peminjaman Ruangan
            $q->where(function($sub) use ($prodiId) {
                $sub->where('type', 'room')
                    ->whereHas('lab', function($l) use ($prodiId) {
                        $l->where('prodi_id', $prodiId);
                    });
            })
            // Jalur 2: Khusus untuk peminjaman Aset
            ->orWhere(function($sub) use ($prodiId) {
                $sub->where('type', 'asset')
                    ->whereHas('reservationItems.asset.lab', function($al) use ($prodiId) {
                        $al->where('prodi_id', $prodiId);
                    });
            });
        });
    } 
    
    // Logic untuk mahasiswa tetap sama
    if ($user->hasRole('Mahasiswa')) {
        $query->where('user_id', $user->id);
        return view('reservations.index_rooms', [
            'reservations' => $query->latest()->paginate(10)
        ]);
    }

    // Filter Pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('transaction_code', 'like', "%{$search}%")
              ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
        });
    }

    return view('admin.reservations.index', [
        'reservations' => $query->latest()->paginate(10)->withQueryString()
    ]);
}

    public function scanIndex()
    {
        return view('admin.scan');
    }

    /**
     * PROSES SCAN QR CODE
     * Menjamin Laboran tidak bisa memproses QR Code dari prodi lain.
     */
    public function scanProcess(Request $request)
    {
        $user = Auth::user();
        $reservation = Reservation::with(['reservationItems.asset.lab', 'lab'])
                                  ->where('transaction_code', $request->qr_code)
                                  ->first();

        if (!$reservation) {
            return response()->json(['success' => false, 'message' => 'Data transaksi tidak ditemukan!']);
        }

        // VALIDASI KEAMANAN: Cek wewenang Prodi sebelum memproses scan
        if ($user->hasRole('Laboran')) {
            $prodiId = $user->prodi_id;
            $authorized = false;

            if ($reservation->type == 'room') {
                $authorized = ($reservation->lab && $reservation->lab->prodi_id == $prodiId);
            } else {
                // Untuk aset, pastikan ada minimal satu item yang milik prodi laboran
                $authorized = $reservation->reservationItems()->whereHas('asset.lab', function($q) use ($prodiId) {
                    $q->where('prodi_id', $prodiId);
                })->exists();
            }

            if (!$authorized) {
                return response()->json(['success' => false, 'message' => 'Gagal! Transaksi ini milik prodi lain.']);
            }
        }

        $now = Carbon::now();

        // Alur Status: Approved -> Borrowed -> Returned
        if ($reservation->status == 'approved') {
            $reservation->update(['status' => 'borrowed']);
            return response()->json(['success' => true, 'message' => "Check-in Berhasil! Barang/Ruangan sedang digunakan."]);
        } 
        
        if ($reservation->status == 'borrowed') {
            $endTime = Carbon::parse($reservation->end_time);
            
            // Hitung Denda jika terlambat
            if ($now->gt($endTime)) {
                $daysLate = $now->diffInDays($endTime) ?: 1;
                $reservation->penalty = $daysLate * 5000; // Contoh denda 5rb/hari
                $reservation->payment_status = 'unpaid';
                $reservation->penalty_status = 'unpaid';
            }
            
            $reservation->status = 'returned';
            $reservation->save();
            return response()->json(['success' => true, 'message' => "Check-out Berhasil! Pengembalian telah dicatat."]);
        }

        return response()->json(['success' => false, 'message' => 'Status transaksi saat ini ('.$reservation->status.') tidak dapat diproses melalui scan.']);
    }

    /**
     * Update Status Pembayaran Denda
     */
    public function payPenalty(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update([
            'payment_status' => 'paid',
            'penalty_status' => 'paid',
            'payment_method' => $request->payment_method ?? 'Cash'
        ]);
        return back()->with('success', 'Pembayaran denda berhasil diverifikasi.');
    }

    /**
     * EXPORT PDF
     * Laporan yang dihasilkan hanya berisi data milik prodi Laboran tersebut.
     */
    public function exportPDF(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month');
        $year = $request->input('year', date('Y'));

        $query = Reservation::with(['user', 'reservationItems.asset.lab', 'lab']);

        // Filter Silo Data untuk Laporan
        if ($user->hasRole('Laboran')) {
            $prodiId = $user->prodi_id;
            $query->where(function($q) use ($prodiId) {
                $q->where(function($sub) use ($prodiId) {
                    $sub->where('type', 'room')->whereHas('lab', fn($l) => $l->where('prodi_id', $prodiId));
                })->orWhere(function($sub) use ($prodiId) {
                    $sub->where('type', 'asset')->whereHas('reservationItems.asset.lab', fn($al) => $al->where('prodi_id', $prodiId));
                });
            });
        }

        if ($month) $query->whereMonth('start_time', $month);
        if ($year) $query->whereYear('start_time', $year);

        $reservations = $query->latest()->get();
        $totalDenda = $reservations->sum('penalty');
        $monthName = $month ? Carbon::createFromDate(null, $month, 1)->translatedFormat('F') : 'Semua Bulan';
        $date = now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('admin.reports.peminjaman_pdf', compact('reservations', 'monthName', 'year', 'totalDenda', 'date'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-peminjaman-' . time() . '.pdf');
    }
    /**
 * MENYETUJUI ATAU MENOLAK TRANSAKSI (Silo Data Protected)
 */
public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $reservation = Reservation::with(['reservationItems.asset.lab', 'lab'])->findOrFail($id);

        if ($user->hasRole('Laboran')) {
            $prodiId = $user->prodi_id;
            $authorized = false;

            if ($reservation->type == 'room') {
                $authorized = ($reservation->lab && $reservation->lab->prodi_id == $prodiId);
            } else {
                $authorized = $reservation->reservationItems()->whereHas('asset.lab', function($q) use ($prodiId) {
                    $q->where('prodi_id', $prodiId);
                })->exists();
            }

            if (!$authorized) {
                abort(403, 'Anda tidak memiliki wewenang untuk mengubah status transaksi prodi lain.');
            }
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_note' => 'required_if:status,rejected|nullable|string|max:255'
        ]);

        $reservation->update([
            'status' => $request->status,
            'rejection_note' => $request->status == 'rejected' ? $request->rejection_note : null,
        ]);

        $pesan = $request->status == 'approved' ? 'Transaksi disetujui!' : 'Transaksi ditolak.';
        return back()->with('success', $pesan);
    }
}