<?php

namespace App\Imports;

use App\Models\Asset;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Agar baca header tabel

class AssetImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Logika: Jika user Laboran, prodi otomatis ikut Laboran
        // Jika Superadmin, ambil dari Excel, kalau kosong default '-'
        $user = Auth::user();
        $prodi = $user->hasRole('Superadmin') ? ($row['prodi'] ?? '-') : $user->prodi;

        return new Asset([
            'name'        => $row['nama_aset'],      // Sesuaikan dengan judul kolom di Excel
            'code'        => $row['kode_aset'],
            'category_id' => $row['id_kategori'],    // Di Excel isi angkanya (1, 2, dll)
            'lab_id'      => $row['id_lab'],         // Di Excel isi angkanya
            'stock'       => $row['stok'],
            'prodi'       => $prodi,
            'status'      => 'available',            // Default status
            'image'       => null,                   // Gambar kosong dulu
        ]);
    }
}