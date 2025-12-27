<?php

namespace App\Imports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Asset([
            'name'        => $row['nama_aset'],
            'code'        => $row['kode_aset'],
            'category_id' => $row['id_kategori'],
            'lab_id'      => $row['id_lab'],
            'stock'       => $row['stok'],
            'prodi'       => $row['prodi'], 
            'status'      => 'available',
            // 'condition' => 'good', <-- SAYA HAPUS BIAR GAK ERROR
        ]);
    }
}