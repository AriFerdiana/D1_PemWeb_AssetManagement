<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Lab;
use App\Models\Asset;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT ROLE
        $roleSuperAdmin = Role::create(['name' => 'Superadmin']);
        $roleLaboran = Role::create(['name' => 'Laboran']);
        $roleMahasiswa = Role::create(['name' => 'Mahasiswa']);
        $roleDosen = Role::create(['name' => 'Dosen']);

        // 2. CONFIGURASI DATA PRODI, LAB, & CONTOH ALAT (Data Lengkap ITENAS)
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
                    'code' => 'SI', 'lab' => 'Lab Uji Bahan & Beton', 'gedung' => 'Gedung 12',
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

        // Variabel untuk menyimpan ID Informatika (buat Ari Ferdiana)
        $idProdiInformatika = null;

        // 3. LOOPING PEMBUATAN DATA (Prodi -> Lab -> Asset)
        foreach ($dataFakultas as $fakultas => $prodis) {
            foreach ($prodis as $namaProdi => $data) {
                // Buat Prodi
                $prodi = Prodi::create([
                    'name' => $namaProdi,
                    'code' => $data['code'] . '-' . rand(100, 999), // Code unik
                    'faculty' => $fakultas,
                    'location_office' => $data['gedung'],
                    'contact_email' => strtolower(str_replace(' ', '', $data['code'])) . '@itenas.ac.id'
                ]);

                // Simpan ID Informatika
                if ($namaProdi === 'Informatika') {
                    $idProdiInformatika = $prodi->id;
                }

                // Buat Lab untuk Prodi tersebut
                $lab = Lab::create([
                    'prodi_id' => $prodi->id,
                    'name' => $data['lab'],
                    'building_name' => $data['gedung'],
                    'room_number' => rand(1, 4) . '0' . rand(1, 9),
                    'capacity' => rand(20, 40),
                    'latitude' => -6.890000 + (rand(0, 1000) / 100000), // Koordinat dummy sekitar Itenas
                    'longitude' => 107.630000 + (rand(0, 1000) / 100000),
                    'description' => 'Fasilitas praktikum untuk mahasiswa ' . $namaProdi
                ]);

                // Buat 5-8 Aset untuk setiap Lab
                foreach ($data['assets'] as $assetName) {
                    for ($i = 1; $i <= rand(2, 3); $i++) {
                        Asset::create([
                            'lab_id' => $lab->id,
                            'name' => $assetName . ' - Unit ' . $i,
                            'serial_number' => strtoupper($data['code']) . '-' . rand(10000, 99999),
                            'description' => 'Aset inventaris ' . $namaProdi,
                            'stock' => 1,
                            'condition' => 'good',
                            'rental_price' => rand(0, 1) ? 0 : 25000, // Kadang gratis, kadang bayar
                            'image_path' => 'assets/dummy.jpg'
                        ]);
                    }
                }
            }
        }

        // 4. BUAT USER
        // A. Superadmin
        $admin = User::create([
            'name' => 'Admin Sarpras Itenas',
            'email' => 'admin@itenas.ac.id',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($roleSuperAdmin);

        // B. Laboran (Contoh Laboran Informatika)
        $laboran = User::create([
            'name' => 'Laboran Informatika',
            'email' => 'laboran_if@itenas.ac.id',
            'password' => Hash::make('password'),
            'prodi_id' => $idProdiInformatika,
        ]);
        $laboran->assignRole($roleLaboran);

        // C. KETUA KELOMPOK D1 (Ari Ferdiana)
        // Pastikan variabel $idProdiInformatika sudah terisi dari looping di atas
        $ari = User::create([
            'name' => 'Ari Ferdiana (Ketua D1)',
            'email' => 'ari.ferdiana@mhs.itenas.ac.id',
            'password' => Hash::make('password'),
            'prodi_id' => $idProdiInformatika, // Otomatis masuk Informatika
        ]);
        $ari->assignRole($roleMahasiswa);

        // D. Mahasiswa Dummy Lain (Dari Prodi Acak selain Informatika)
        // Ambil ID prodi acak
        $randomProdi = Prodi::where('name', '!=', 'Informatika')->inRandomOrder()->first();
        $mhsLain = User::create([
            'name' => 'Mahasiswa ' . $randomProdi->name,
            'email' => 'mhs_lain@mhs.itenas.ac.id',
            'password' => Hash::make('password'),
            'prodi_id' => $randomProdi->id,
        ]);
        $mhsLain->assignRole($roleMahasiswa);
    }
}