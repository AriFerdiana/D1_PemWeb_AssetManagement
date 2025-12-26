<?php

namespace App\Imports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssetsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * Map data Excel ke Database
    */
    public function model(array $row)
    {
        return new Asset([
            'name'        => $row['nama_aset'], // Sesuai header di Excel
            'category_id' => $row['kategori_id'],
            'code'        => $row['kode_aset'],
            'status'      => 'available', // Default status
            'description' => $row['deskripsi'] ?? null,
        ]);
    }

    /**
     * Validasi tiap baris Excel
     */
    public function rules(): array
    {
        return [
            'nama_aset'   => 'required|string',
            'kode_aset'   => 'required|unique:assets,code', // Cek unik kode
            'kategori_id' => 'required|integer|exists:categories,id',
        ];
    }
}