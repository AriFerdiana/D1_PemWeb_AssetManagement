<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // PENTING: Tambahan untuk generate slug
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan Foreign Key Check (Agar aman saat hapus data)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Kosongkan Tabel Kategori (Hapus Data Lama)
        DB::table('categories')->truncate();

        // 3. Hidupkan Kembali Foreign Key Check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 4. Data Kategori Baru (6 Kategori Utama)
        $categories = [
            [
                'name' => 'Komputer & Gadget',
                'description' => 'Laptop, PC, Tablet, iPad, dan aksesoris komputer (Informatika/SI).',
            ],
            [
                'name' => 'Multimedia & Fotografi',
                'description' => 'Kamera, Tripod, Lighting, Lensa, Audio Recorder (DKV, Desain Produk, PWK).',
            ],
            [
                'name' => 'Alat Ukur & Survey',
                'description' => 'Theodolite, Total Station, Waterpass, GPS Geodetik (Sipil, Geodesi, Mesin).',
            ],
            [
                'name' => 'Elektronika & Kelistrikan',
                'description' => 'Osiloskop, Multimeter, Power Supply, Modul IoT, Arduino (Elektro).',
            ],
            [
                'name' => 'Mesin & Perkakas',
                'description' => 'Bor, Gerinda, Mesin Bubut Mini, 3D Printer, Tools Kit (Mesin, Desain Produk).',
            ],
            [
                'name' => 'Peralatan Laboratorium',
                'description' => 'Gelas Ukur, Mikroskop, PH Meter, Timbangan Digital (Kimia, Lingkungan).',
            ],
        ];

        // 5. Masukkan ke Database
        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name' => $cat['name'],
                
                // PERBAIKAN: Generate slug otomatis dari nama (contoh: "Komputer & Gadget" jadi "komputer-gadget")
                'slug' => Str::slug($cat['name']), 
                
                'description' => $cat['description'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}