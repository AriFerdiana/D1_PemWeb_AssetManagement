<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil Seeder secara berurutan
        // Urutan ini PENTING agar tidak error (Relasi Database)
        
        $this->call([
            // 1. Data Master (Induk)
            ProdiSeeder::class,      // Wajib duluan (User & Lab butuh ini)
            CategorySeeder::class,   // Wajib duluan (Aset butuh ini)
            
            // 2. User & Role
            UserSeeder::class,       // Membuat Admin, Laboran, Mahasiswa
            
            // 3. Data Lab & Ruangan
            RoomSeeder::class,       // Membuat Data Lab & Ruangan
            
            // 4. Data Aset (Terakhir)
            AssetSeeder::class,      // Membutuhkan Lab & Kategori yang sudah dibuat di atas
        ]);
    }
}