<?php

namespace App\Imports;

use App\Models\Lab;
use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LabsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Mencari ID Prodi berdasarkan nama prodi di Excel
        $prodi = Prodi::where('name', 'like', '%' . $row['prodi'] . '%')->first();

        return new Lab([
            'name'          => $row['nama_lab'],
            'prodi_id'      => $prodi ? $prodi->id : null, 
            'building_name' => $row['lokasi'], // Map 'lokasi' di Excel ke 'building_name' di DB
            'capacity'      => $row['kapasitas'] ?? 0,
            'description'   => $row['deskripsi'] ?? '-',
            'latitude'      => null,
            'longitude'     => null,
        ]);
    }
}