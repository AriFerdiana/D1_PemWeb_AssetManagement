<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BERSIHKAN DATA LAMA
        Schema::disableForeignKeyConstraints();
        User::truncate();
        DB::table('model_has_roles')->truncate();
        Schema::enableForeignKeyConstraints();

        // 2. PASTIKAN ROLE TERSEDIA
        $roleSuperadmin = Role::firstOrCreate(['name' => 'Superadmin']);
        $roleLaboran    = Role::firstOrCreate(['name' => 'Laboran']);
        $roleMahasiswa  = Role::firstOrCreate(['name' => 'Mahasiswa']);

        // ====================================================
        // A. BUAT AKUN SUPERADMIN
        // ====================================================
        $superadmin = User::create([
            'name'      => 'Super Administrator',
            'email'     => 'superadmin@itenas.ac.id',
            'password'  => Hash::make('password123'),
            'prodi_id'  => null,
            'nim'       => 'ADMIN001',
        ]);
        $superadmin->assignRole($roleSuperadmin);


        // ====================================================
        // B. BUAT AKUN LABORAN (PERBAIKAN NIM DUPLICATE)
        // ====================================================
        $allProdi = Prodi::all(); 

        foreach ($allProdi as $prodi) {
            // Bikin email dari nama prodi (hapus spasi, lowercase)
            $emailName = strtolower(str_replace(' ', '', $prodi->name));
            
            // PERBAIKAN DI SINI:
            // Jangan ambil substr nama, tapi ambil 'code' unik dari tabel prodis (IF, SI, MS, dll)
            // Pastikan di database kolom 'code' sudah terisi (dari ProdiSeeder)
            $kodeProdi = $prodi->code ?? strtoupper(substr($prodi->name, 0, 3)); 

            $laboran = User::create([
                'name'      => 'Laboran ' . $prodi->name,
                'email'     => 'laboran.' . $emailName . '@itenas.ac.id',
                'password'  => Hash::make('password123'),
                'prodi_id'  => $prodi->id,
                'nim'       => 'LAB-' . $kodeProdi, // Contoh: LAB-IF, LAB-SI, LAB-MS
            ]);
            $laboran->assignRole($roleLaboran);
        }


        // ====================================================
        // C. BUAT AKUN MAHASISWA
        // ====================================================
        $prodiInformatika = Prodi::where('name', 'LIKE', '%Informatika%')->first();
        $idInformatika = $prodiInformatika ? $prodiInformatika->id : null;

        $ari = User::create([
            'name'      => 'Ari Ferdiana',
            'email'     => 'ari.ferdiana@mhs.itenas.ac.id',
            'password'  => Hash::make('password123'),
            'prodi_id'  => $idInformatika,
            'nim'       => '152024001',   
        ]);
        $ari->assignRole($roleMahasiswa);
    }
}