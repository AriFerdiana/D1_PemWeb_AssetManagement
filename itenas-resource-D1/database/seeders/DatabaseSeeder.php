<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Lab;
use App\Models\Asset;
use App\Models\Category;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. BERSIHKAN DATA LAMA (Reset Table agar bersih)
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Prodi::truncate();
        Lab::truncate();
        Asset::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. BUAT ROLE
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Superadmin']);
        $roleLaboran    = Role::firstOrCreate(['name' => 'Laboran']);
        $roleMahasiswa  = Role::firstOrCreate(['name' => 'Mahasiswa']);
        $roleDosen      = Role::firstOrCreate(['name' => 'Dosen']);

        // 2. BUAT KATEGORI (PENTING: Agar filter kategori di Controller jalan)
        $catElektronik = Category::create(['name' => 'Elektronik & Komputer']);
        $catFurniture  = Category::create(['name' => 'Furniture & Mebel']);
        $catAlatBerat  = Category::create(['name' => 'Alat Berat & Mesin']);
        $catAlatUkur   = Category::create(['name' => 'Alat Ukur']);

        // 3. CONFIGURASI DATA LENGKAP ITENAS
        $dataFakultas = [
            'FTI' => [
                'Informatika' => [
                    'code' => 'IF', 'lab' => 'Lab Rekayasa Perangkat Lunak', 'gedung' => 'Gedung 14 Lt.3', 
                    'assets' => ['PC High Spec ROG', 'Monitor 24 Inch', 'Server Rack', 'VR Headset']
                ],
                'Teknik Elektro' => [
                    'code' => 'EL', 'lab' => 'Lab Dasar Elektronika', 'gedung' => 'Gedung 14 Lt.2',
                    'assets' => ['Osiloskop Digital', 'Solder Station', 'Power Supply DC', 'Multimeter Fluke']
                ],
                'Teknik Mesin' => [
                    'code' => 'MS', 'lab' => 'Lab Produksi & CNC', 'gedung' => 'Gedung 10',
                    'assets' => ['Mesin Bubut', 'Mesin CNC Milling', 'Bor Duduk', 'Gerinda Tangan']
                ],
                'Teknik Industri' => [
                    'code' => 'TI', 'lab' => 'Lab Ergonomi & APK', 'gedung' => 'Gedung 10 Lt.3',
                    'assets' => ['Kursi Antropometri', 'Treadmill Test', 'Sound Level Meter', 'Lux Meter']
                ],
                'Teknik Kimia' => [
                    'code' => 'TK', 'lab' => 'Lab Operasi Teknik Kimia', 'gedung' => 'Gedung 19',
                    'assets' => ['Reaktor Kaca', 'Gelas Ukur Pyrex', 'Neraca Analitik', 'Sentrifuse']
                ],
                'Sistem Informasi' => [
                    'code' => 'SI', 'lab' => 'Lab Enterprise System', 'gedung' => 'Gedung 14 Lt.3',
                    'assets' => ['PC All-in-One', 'Smart TV Presentation', 'Tablet Android', 'Fingerprint Scanner']
                ],
            ],
            'FTSP' => [
                'Teknik Sipil' => [
                    'code' => 'SIP', 'lab' => 'Lab Uji Bahan & Beton', 'gedung' => 'Gedung 12',
                    'assets' => ['Mesin Uji Tekan Beton', 'Theodolite Digital', 'Waterpass', 'Hammer Test']
                ],
                'Teknik Geodesi' => [
                    'code' => 'GD', 'lab' => 'Lab Fotogrametri', 'gedung' => 'Gedung 12',
                    'assets' => ['Drone Pemetaan DJI', 'Total Station', 'GPS Geodetik', 'Kompas Geologi']
                ],
                'Perencanaan Wilayah dan Kota' => [
                    'code' => 'PWK', 'lab' => 'Studio Perencanaan Wilayah (GIS)', 'gedung' => 'Gedung 18',
                    'assets' => ['Plotter A0 HP DesignJet', 'Workstation GIS', 'Peta Digital', 'Meja Diskusi']
                ],
                'Teknik Lingkungan' => [
                    'code' => 'TL', 'lab' => 'Lab Kualitas Air', 'gedung' => 'Gedung 18',
                    'assets' => ['pH Meter Digital', 'DO Meter', 'Tabung Reaksi', 'Mikroskop Binokuler']
                ],
            ],
            'FAD' => [
                'Desain Komunikasi Visual' => [
                    'code' => 'DKV', 'lab' => 'Studio Fotografi & Multimedia', 'gedung' => 'Gedung 20',
                    'assets' => ['Kamera Sony Alpha', 'Lighting Studio Set', 'Green Screen', 'MacBook Pro M2']
                ],
                'Desain Produk' => [
                    'code' => 'DP', 'lab' => 'Workshop Desain Produk', 'gedung' => 'Gedung 20',
                    'assets' => ['3D Printer BambuLab', 'Scroll Saw', 'Airbrush Kit', 'Heat Gun']
                ],
                'Arsitektur' => [
                    'code' => 'AR', 'lab' => 'Studio Perancangan Arsitektur', 'gedung' => 'Gedung 5',
                    'assets' => ['Meja Gambar Arsitek', 'Maket Cutter', 'Laser Distance Meter', 'Lampu Meja LED']
                ],
                'Desain Interior' => [
                    'code' => 'DI', 'lab' => 'Lab Material Interior', 'gedung' => 'Gedung 5',
                    'assets' => ['Sampel HPL', 'Mesin Jahit Industri', 'Laser Cutting Mini', 'Pantone Color Guide']
                ],
            ]
        ];

        // Variabel penampung ID Prodi Informatika
        $idProdiInformatika = null;

        // 4. LOOPING UTAMA (Prodi -> Lab -> Asset -> LABORAN)
        foreach ($dataFakultas as $fakultas => $prodis) {
            foreach ($prodis as $namaProdi => $data) {
                
                // A. Buat Prodi
                $prodi = Prodi::create([
                    'name' => $namaProdi,
                    'code' => $data['code'],
                    'faculty' => $fakultas,
                    'location_office' => $data['gedung'],
                    'contact_email' => strtolower(str_replace(' ', '', $data['code'])) . '@itenas.ac.id'
                ]);

                // Simpan ID jika Informatika (Untuk Ari Ferdiana nanti)
                if ($namaProdi === 'Informatika') {
                    $idProdiInformatika = $prodi->id;
                }

                // B. BUAT AKUN LABORAN UNTUK PRODI INI
                $emailPrefix = strtolower(str_replace(' ', '', $namaProdi));
                $emailPrefix = str_replace('&', 'dan', $emailPrefix); // Jaga-jaga karakter aneh

                $laboran = User::create([
                    'name'      => 'Admin Lab ' . $namaProdi,
                    'email'     => 'laboran.' . $data['code'] . '@itenas.ac.id', // Pakai kode prodi biar pendek (laboran.if@itenas.ac.id)
                    'password'  => Hash::make('password123'),
                    'prodi_id'  => $prodi->id, // PENTING: Relasi ke tabel prodi
                ]);
                $laboran->assignRole($roleLaboran);


                // C. Buat Lab
                $lab = Lab::create([
                    'prodi_id' => $prodi->id,
                    'name' => $data['lab'],
                    'building_name' => $data['gedung'],
                    'room_number' => rand(1, 4) . '0' . rand(1, 9),
                    'capacity' => rand(20, 40),
                    'description' => 'Fasilitas praktikum ' . $namaProdi
                ]);

                // D. Buat Aset
                foreach ($data['assets'] as $assetName) {
                    for ($i = 1; $i <= rand(2, 3); $i++) {
                        
                        // Logika random kategori biar data bervariasi
                        $randomCatId = $catElektronik->id;
                        if (str_contains($assetName, 'Meja') || str_contains($assetName, 'Kursi')) $randomCatId = $catFurniture->id;
                        if (str_contains($assetName, 'Mesin') || str_contains($assetName, 'Bubut')) $randomCatId = $catAlatBerat->id;
                        if (str_contains($assetName, 'Meter') || str_contains($assetName, 'Ukur')) $randomCatId = $catAlatUkur->id;

                        Asset::create([
                            'lab_id' => $lab->id,
                            'category_id' => $randomCatId, 
                            'prodi' => $namaProdi, // String nama prodi untuk kompatibilitas filter
                            'name' => $assetName . ' - Unit ' . $i,
                            'code' => $data['code'] . '-' . rand(1000, 9999) . '-' . $i,
                            'stock' => 1,
                            'status' => 'available',
                            'image' => null
                        ]);
                    }
                }
            }
        }

        // 5. BUAT USER SPESIFIK LAINNYA

        // A. Superadmin (Bisa lihat semua)
        $admin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@itenas.ac.id',
            'password' => Hash::make('password123'),
            'prodi_id' => null, 
        ]);
        $admin->assignRole($roleSuperAdmin);

        // B. Ari Ferdiana (Mahasiswa Informatika)
        if ($idProdiInformatika) {
            $ari = User::create([
                'name' => 'Ari Ferdiana',
                'nim'  => '152024003', // Tambahkan NIM sesuai request & migrasi
                'email' => 'ari.ferdiana@mhs.itenas.ac.id',
                'password' => Hash::make('password123'),
                'prodi_id' => $idProdiInformatika,
            ]);
            $ari->assignRole($roleMahasiswa);
        }
    }
}