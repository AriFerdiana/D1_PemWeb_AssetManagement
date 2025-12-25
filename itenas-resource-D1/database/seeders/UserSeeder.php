<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role; // Pastikan package Spatie sudah terinstall

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. BERSIHKAN DATABASE LAMA (Reset)
        // Disable Foreign Key Check biar bisa truncate tanpa error
        Schema::disableForeignKeyConstraints();
        
        // Kosongkan tabel users dan role assignments
        User::truncate();
        DB::table('model_has_roles')->truncate(); 
        // Role::truncate(); // Uncomment jika ingin reset role juga
        
        Schema::enableForeignKeyConstraints();

        // 2. PASTIKAN ROLE TERSEDIA (Create if not exists)
        // Gunakan firstOrCreate agar tidak error jika role sudah ada
        $roleSuperadmin = Role::firstOrCreate(['name' => 'Superadmin']);
        $roleLaboran    = Role::firstOrCreate(['name' => 'Laboran']);
        $roleMahasiswa  = Role::firstOrCreate(['name' => 'Mahasiswa']);

        // ==========================================
        // 3. BUAT AKUN SUPERADMIN (Dewa)
        // ==========================================
        $superadmin = User::create([
            'name'      => 'Super Administrator',
            'email'     => 'superadmin@itenas.ac.id',
            'password'  => Hash::make('password123'), // Default password
            'prodi'     => null, // Superadmin tidak terikat prodi
        ]);
        $superadmin->assignRole($roleSuperadmin);

        // ==========================================
        // 4. BUAT AKUN LABORAN (Per Prodi)
        // ==========================================
        $daftarProdi = [
            'Informatika',
            'Sistem Informasi',
            'Teknik Industri',
            'Teknik Elektro',
            'Teknik Sipil',
            'Arsitektur',
            'Desain Komunikasi Visual'
        ];

        foreach ($daftarProdi as $prodi) {
            // Bikin username yg gampang, misal: laboran.if, laboran.si
            $emailPrefix = strtolower(str_replace(' ', '', $prodi)); 
            
            $laboran = User::create([
                'name'      => 'Admin Lab ' . $prodi,
                'email'     => 'laboran.' . $emailPrefix . '@itenas.ac.id',
                'password'  => Hash::make('password123'),
                'prodi'     => $prodi, // PENTING: Ini kunci filter data nanti
            ]);
            $laboran->assignRole($roleLaboran);
        }

        // ==========================================
        // 5. BUAT AKUN MAHASISWA (Ari Ferdiana)
        // ==========================================
        $ari = User::create([
            'name'      => 'Ari Ferdiana',
            'email'     => 'ari.ferdiana@mhs.itenas.ac.id',
            'password'  => Hash::make('password123'),
            'prodi'     => 'Informatika', // Asumsi Ari anak IF
            'nim'       => '152024001',   // Tambahkan kolom NIM jika ada di tabelmu
        ]);
        $ari->assignRole($roleMahasiswa);
    }
}