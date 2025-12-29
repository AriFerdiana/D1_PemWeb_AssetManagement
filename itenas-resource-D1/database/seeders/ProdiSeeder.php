<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('prodis')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $prodis = [
            ['name' => 'Teknik Informatika', 'code' => 'IF', 'faculty' => 'FTI'],
            ['name' => 'Sistem Informasi', 'code' => 'SI', 'faculty' => 'FTI'],
            ['name' => 'Teknik Industri', 'code' => 'TI', 'faculty' => 'FTI'],
            ['name' => 'Teknik Elektro', 'code' => 'EL', 'faculty' => 'FTI'],
            ['name' => 'Teknik Mesin', 'code' => 'MS', 'faculty' => 'FTI'],
            ['name' => 'Teknik Kimia', 'code' => 'TK', 'faculty' => 'FTI'],
            ['name' => 'Teknik Sipil', 'code' => 'SP', 'faculty' => 'FTSP'],
            ['name' => 'Teknik Geodesi', 'code' => 'GD', 'faculty' => 'FTSP'],
            ['name' => 'Perencanaan Wilayah dan Kota', 'code' => 'PWK', 'faculty' => 'FTSP'],
            ['name' => 'Teknik Lingkungan', 'code' => 'TL', 'faculty' => 'FTSP'],
            ['name' => 'Desain Komunikasi Visual', 'code' => 'DKV', 'faculty' => 'FAD'],
            ['name' => 'Desain Produk', 'code' => 'DP', 'faculty' => 'FAD'],
            ['name' => 'Desain Interior', 'code' => 'DI', 'faculty' => 'FAD'],
            ['name' => 'Arsitektur', 'code' => 'AR', 'faculty' => 'FAD'],
        ];

        foreach ($prodis as $prodi) {
            DB::table('prodis')->insert([
                'name' => $prodi['name'],
                'code' => $prodi['code'],
                'faculty' => $prodi['faculty'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}