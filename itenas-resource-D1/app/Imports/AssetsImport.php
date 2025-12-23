<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Lab;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Agar membaca header baris pertama

class AssetsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Pastikan Excel punya header: nama_aset, kode_aset, stok, kode_ruangan
        
        // Cari ID Lab berdasarkan kode ruangan di excel
        $lab = Lab::where('code', $row['kode_ruangan'])->first();

        return new Asset([
            'name'     => $row['nama_aset'],
            'code'     => $row['kode_aset'],
            'stock'    => $row['stok'],
            'lab_id'   => $lab ? $lab->id : null, // Jika kode lab salah, biarkan kosong
            'status'   => 'good', // Default kondisi baik
            'image'    => null,
        ]);
    }
}