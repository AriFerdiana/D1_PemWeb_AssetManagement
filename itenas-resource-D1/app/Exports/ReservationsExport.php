<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Mengambil data dari database
    */
    public function collection()
    {
        // Ambil data transaksi beserta user dan barangnya
        return Reservation::with(['user.prodi', 'reservationItems.asset'])->get();
    }

    /**
    * Judul Kolom (Header)
    */
    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Peminjam',
            'Prodi',
            'Barang Dipinjam',
            'Tgl Mulai',
            'Tgl Selesai',
            'Keperluan',
            'Status',
            'Tgl Pengajuan',
        ];
    }

    /**
    * Isi Data per Baris
    */
    public function map($reservation): array
    {
        // Gabungkan nama barang jika meminjam banyak item sekaligus
        $items = $reservation->reservationItems->map(function($item) {
            return $item->asset->name . ' (' . $item->quantity . ' unit)';
        })->implode(', ');

        return [
            $reservation->transaction_code,
            $reservation->user->name,
            $reservation->user->prodi->name ?? 'Staff/Umum',
            $items,
            $reservation->start_time,
            $reservation->end_time,
            $reservation->purpose,
            strtoupper($reservation->status),
            $reservation->created_at->format('d-m-Y'),
        ];
    }
}