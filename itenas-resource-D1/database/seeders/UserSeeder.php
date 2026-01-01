<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker; // <--- Import Faker

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan Faker Indonesia biar datanya nyata
        $faker = Faker::create('id_ID');

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
            'phone'     => '081200000000',     // <--- Tambahan
            'address'   => 'Gedung Rektorat Itenas', // <--- Tambahan
        ]);
        $superadmin->assignRole($roleSuperadmin);


        // ====================================================
        // B. BUAT AKUN LABORAN (OTOMATIS SESUAI PRODI)
        // ====================================================
        $allProdi = Prodi::all(); 

        foreach ($allProdi as $prodi) {
            // Bersihkan nama prodi untuk email (hapus spasi, lowercase)
            $emailName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $prodi->name));
            
            // Ambil kode prodi (misal: IF, SI, AR) atau ambil 3 huruf pertama
            $kodeProdi = $prodi->code ?? strtoupper(substr($prodi->name, 0, 3)); 

            $laboran = User::create([
                'name'      => 'Laboran ' . $prodi->name,
                'email'     => 'laboran.' . $emailName . '@itenas.ac.id',
                'password'  => Hash::make('password123'),
                'prodi_id'  => $prodi->id,
                'nim'       => 'LAB-' . $kodeProdi . '-' . rand(10,99), // Tambah random biar unik
                'phone'     => $faker->phoneNumber, // <--- Pakai Faker
                'address'   => 'Lab ' . $prodi->name . ' Gd. 3 Lt. 2',
            ]);
            $laboran->assignRole($roleLaboran);
        }


        // ====================================================
        // C. BUAT AKUN MAHASISWA SPESIFIK (ARI)
        // ====================================================
        // Cari ID Prodi Informatika (pastikan namanya sesuai di database ProdiSeeder)
        $prodiInformatika = Prodi::where('name', 'LIKE', '%Informatika%')->first();
        $idInformatika = $prodiInformatika ? $prodiInformatika->id : ($allProdi->first()->id ?? null);

        $ari = User::create([
            'name'      => 'Ari Ferdiana',
            'email'     => 'ari.ferdiana@mhs.itenas.ac.id',
            'password'  => Hash::make('password123'),
            'prodi_id'  => $idInformatika,
            'nim'       => '152024002', // Sesuaikan dengan error log tadi (ujungnya 2)
            'phone'     => '081234567890',
            'address'   => 'Jl. PHH Mustofa No. 23, Bandung',
        ]);
        $ari->assignRole($roleMahasiswa);


        // ====================================================
        // D. DATA DUMMY MAHASISWA (SYARAT UAS MINIMAL 15)
        // ====================================================
        // Kita buat 20 mahasiswa acak tambahan
        for ($i = 1; $i <= 20; $i++) {
            // Pilih prodi secara acak
            $randomProdi = $allProdi->random();
            
            // Generate NRP Acak (misal: 152024xxx)
            $randomNRP = '152024' . str_pad($i + 10, 3, '0', STR_PAD_LEFT); 

            $dummyMhs = User::create([
                'name'      => $faker->name, // Nama Indonesia Acak
                'email'     => $faker->unique()->userName . '@mhs.itenas.ac.id',
                'password'  => Hash::make('password123'),
                'prodi_id'  => $randomProdi->id,
                'nim'       => $randomNRP,
                'phone'     => $faker->phoneNumber,
                'address'   => $faker->address,
            ]);
            $dummyMhs->assignRole($roleMahasiswa);
        }
    }
}