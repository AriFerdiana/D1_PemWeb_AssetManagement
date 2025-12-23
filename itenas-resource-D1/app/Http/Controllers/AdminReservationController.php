<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon; // Pastikan import Carbon untuk urusan waktu

class AdminReservationController extends Controller
{
    public function scanIndex()
    {
        return view('admin.scan');
    }

    public function scanProcess(Request $request)
    {
        // Mencari reservasi berdasarkan QR Code (Transaction Code)
        $reservation = Reservation::where('transaction_code', $request->qr_code)->first();

        if (!$reservation) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan!']);
        }

        $now = Carbon::now();

        // 1. LOGIC CHECK-IN (Mahasiswa mengambil barang)
        if ($reservation->status == 'approved') {
            $reservation->update([
                'status' => 'borrowed',
                // Opsional: simpan waktu pengambilan aktual jika kamu punya kolomnya
                // 'actual_borrowed_at' => $now 
            ]);

            return response()->json([
                'success' => true, 
                'message' => "Check-in Berhasil! Barang siap diambil oleh {$reservation->user->name}."
            ]);
        } 
        
        // 2. LOGIC CHECK-OUT (Mahasiswa mengembalikan barang) + HITUNG DENDA
        if ($reservation->status == 'borrowed') {
            $endTime = Carbon::parse($reservation->end_time);
            $penalty = 0;
            $statusPesan = "Check-out Berhasil! Barang telah dikembalikan tepat waktu.";

            // Cek apakah sekarang sudah melewati batas waktu (end_time)
            if ($now->gt($endTime)) {
                // Hitung selisih hari. diffInDays akan menghasilkan 0 jika telat beberapa jam di hari yang sama.
                // Kita gunakan ceil (pembulatan ke atas) atau logika manual agar telat dikit tetap hitung 1 hari.
                $daysLate = $now->diffInDays($endTime);
                
                // Jika telat jam (di hari yang sama) atau selisih hari >= 0
                // Kita asumsikan telat 1 menit pun masuk hitungan 1 hari denda sesuai kebijakan kampus.
                $daysLate = ($daysLate == 0) ? 1 : $daysLate;

                $penalty = $daysLate * 5000;
                $statusPesan = "Check-out Berhasil! TELAT {$daysLate} hari. Denda: Rp " . number_format($penalty);
                
                // Update denda dan status pembayaran ke database
                $reservation->penalty = $penalty;
                $reservation->payment_status = 'unpaid'; // Set jadi belum bayar
            }

            $reservation->status = 'returned';
            // Simpan waktu pengembalian aktual
            $reservation->actual_returned_at = $now; 
            $reservation->save();

            return response()->json([
                'success' => true, 
                'message' => $statusPesan
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Status transaksi tidak valid untuk scan ini.']);
    }

    /**
     * Fitur Pembayaran Denda (Dipanggil dari halaman Histori Admin)
     */
    public function payPenalty(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        $request->validate([
            'payment_method' => 'required|in:Cash,Transfer,QRIS'
        ]);

        $reservation->update([
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method
        ]);

        return back()->with('success', 'Denda sebesar Rp ' . number_format($reservation->penalty) . ' telah dibayar via ' . $request->payment_method);
    }

    public function exportPDF(Request $request)
{
    // Ambil data berdasarkan bulan dan tahun (default bulan ini)
    $month = $request->month ?? date('m');
    $year = $request->year ?? date('Y');

    $reservations = Reservation::with(['user', 'reservationItems.asset'])
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->get();

    $totalDenda = $reservations->where('payment_status', 'paid')->sum('penalty');

    // Menyiapkan data untuk dikirim ke view PDF
    $data = [
        'title' => 'Laporan Peminjaman Aset Itenas',
        'date' => date('d/m/Y'),
        'reservations' => $reservations,
        'month' => $month,
        'year' => $year,
        'totalDenda' => $totalDenda
    ];

    // Load view khusus PDF
    $pdf = Pdf::loadView('admin.reports.peminjaman_pdf', $data);

    // Download file PDF
    return $pdf->download('Laporan-Peminjaman-'.$month.'-'.$year.'.pdf');
}
}