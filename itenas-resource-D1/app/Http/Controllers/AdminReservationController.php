<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller; 

class AdminReservationController extends Controller
{
    /**
     * Menampilkan daftar transaksi dengan Filter Tanggal
     */
    public function index(Request $request)
    {
        // 1. Mulai Query
        $query = Reservation::with(['user', 'asset', 'lab']);

        // 2. LOGIKA SEARCH (Keyword)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%") 
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 3. LOGIKA FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. LOGIKA FILTER TANGGAL (BARU)
        // Jika user mengisi Tanggal Awal
        if ($request->filled('start_date')) {
            $query->whereDate('start_time', '>=', $request->start_date);
        }
        // Jika user mengisi Tanggal Akhir
        if ($request->filled('end_date')) {
            $query->whereDate('start_time', '<=', $request->end_date);
        }

        // 5. Ambil data (Pagination)
        $reservations = $query->latest()->paginate(10)->withQueryString();

        // Mengarah ke file view Anda
        return view('reservations.index_assets', compact('reservations'));
    }

    // --- FUNCTION LAIN TETAP SAMA ---

    public function scanIndex()
    {
        return view('admin.scan');
    }

    public function scanProcess(Request $request)
    {
        $reservation = Reservation::where('transaction_code', $request->qr_code)->first();

        if (!$reservation) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan!']);
        }

        $now = Carbon::now();

        if ($reservation->status == 'approved') {
            $reservation->update(['status' => 'borrowed']);
            return response()->json(['success' => true, 'message' => "Check-in Berhasil!"]);
        } 
        
        if ($reservation->status == 'borrowed') {
            $endTime = Carbon::parse($reservation->end_time);
            
            if ($now->gt($endTime)) {
                $daysLate = $now->diffInDays($endTime) ?: 1;
                $reservation->penalty = $daysLate * 50000; 
                $reservation->payment_status = 'unpaid';
                $reservation->penalty_status = 'unpaid';
            }
            
            $reservation->status = 'returned';
            $reservation->save();
            return response()->json(['success' => true, 'message' => "Check-out Berhasil!"]);
        }

        return response()->json(['success' => false, 'message' => 'Status tidak valid untuk scan.']);
    }

    public function payPenalty(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update([
            'payment_status' => 'paid',
            'penalty_status' => 'paid',
            'payment_method' => $request->payment_method ?? 'Cash'
        ]);
        return back()->with('success', 'Denda telah dibayar.');
    }

    public function exportPDF(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $reservations = Reservation::with(['user', 'lab'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        $data = [
            'title' => 'Laporan Peminjaman Lab Itenas',
            'reservations' => $reservations,
            'totalDenda' => $reservations->where('payment_status', 'paid')->sum('penalty')
        ];

        $pdf = Pdf::loadView('admin.reports.peminjaman_pdf', $data);
        return $pdf->download('Laporan-Reservasi.pdf');
    }
}