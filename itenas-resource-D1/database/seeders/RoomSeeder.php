<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Prodi;
use Carbon\Carbon;

class RoomSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan Data Lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('labs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Helper Cerdas untuk ambil ID Prodi
        $getProdiId = function ($name) {
            $prodi = DB::table('prodis')->where('name', 'LIKE', '%' . $name . '%')->first();
            return $prodi ? $prodi->id : null;
        };

        // 3. DAFTAR KOORDINAT GEDUNG
        $coordinates = [
            'Gedung 20' => ['lat' => -6.8977424301213, 'long' => 107.63583070898592],
            'Gedung 18' => ['lat' => -6.897470824919272, 'long' => 107.63575828934277],
            'Gedung 14' => ['lat' => -6.897294888912583, 'long' => 107.63550566353528],
            'Gedung 4'  => ['lat' => -6.897087670274965, 'long' => 107.63578235353688],
            'Gedung 16' => ['lat' => -6.897151777471298, 'long' => 107.63594238598955],
            'Gedung 17' => ['lat' => -6.896825845899719, 'long' => 107.63589228448762],
            'Gedung 19' => ['lat' => -6.896539997782045, 'long' => 107.6357725183302],
            'Gedung 22' => ['lat' => -6.89622246688361, 'long' => 107.63581580145171],
            'Gedung 1'  => ['lat' => -6.89624930626218, 'long' => 107.63617232515844],
            'Gedung 8'  => ['lat' => -6.896016233279208, 'long' => 107.63668076999645],
            'Gedung 9'  => ['lat' => -6.8966413729591105, 'long' => 107.63691102391842],
            'Gedung 12' => ['lat' => -6.8969409954267, 'long' => 107.63678040606568],
            'GSG'       => ['lat' => -6.897339084203305, 'long' => 107.63703204813632],
            'Gedung 2'  => ['lat' => -6.897237530977148, 'long' => 107.63754965337216],
            'Gedung 3'  => ['lat' => -6.897042548719216, 'long' => 107.6375414698902],
            'Gedung 21' => ['lat' => -6.896768354783201, 'long' => 107.637594662523],
            'Gedung 11' => ['lat' => -6.897018175931333, 'long' => 107.6372264058343],
            'Gedung 10' => ['lat' => -6.896646490760693, 'long' => 107.63730414891302],
        ];

        $defaultLat = '-6.897638633535351';
        $defaultLong = '107.63589577429032';

        // 4. DAFTAR RUANGAN (SUDAH DISESUAIKAN KAPASITASNYA)
        $rooms = [
            // --- FTI ---
            ['name' => 'Lab. Jaringan Komputer', 'building_name' => 'Gedung 2', 'prodi_id' => $getProdiId('Informatika'), 'room_number' => '02201', 'capacity' => 45, 'desc' => 'Praktikum jaringan & keamanan siber.'],
            ['name' => 'Lab. Basis Data', 'building_name' => 'Gedung 2', 'prodi_id' => $getProdiId('Informatika'), 'room_number' => '02202', 'capacity' => 45, 'desc' => 'Perancangan database & SQL.'],
            ['name' => 'Lab. Rekayasa Perangkat Lunak', 'building_name' => 'Gedung 2', 'prodi_id' => $getProdiId('Informatika'), 'room_number' => '02203', 'capacity' => 40, 'desc' => 'Coding dan pengembangan software.'],
            
            ['name' => 'Lab. Komputer & SI', 'building_name' => 'Gedung 4', 'prodi_id' => $getProdiId('Sistem Informasi'), 'room_number' => '04205', 'capacity' => 50, 'desc' => 'Pengembangan SI & Bisnis.'],

            ['name' => 'Lab. Teknik Energi Elektrik', 'building_name' => 'Gedung 20', 'prodi_id' => $getProdiId('Elektro'), 'room_number' => '20101', 'capacity' => 30, 'desc' => 'Praktikum arus kuat.'],
            ['name' => 'Lab. Teknik Elektronika', 'building_name' => 'Gedung 20', 'prodi_id' => $getProdiId('Elektro'), 'room_number' => '20102', 'capacity' => 30, 'desc' => 'Rangkaian elektronika.'],

            ['name' => 'Lab. Teknik Produksi', 'building_name' => 'Gedung 6', 'prodi_id' => $getProdiId('Mesin'), 'room_number' => '06101', 'capacity' => 25, 'desc' => 'Mesin perkakas & manufaktur.'],
            ['name' => 'Lab. Konversi Energi', 'building_name' => 'Gedung 11', 'prodi_id' => $getProdiId('Mesin'), 'room_number' => '11105', 'capacity' => 20, 'desc' => 'Mesin konversi energi.'],

            ['name' => 'Lab. APK & Ergonomi', 'building_name' => 'Gedung 10', 'prodi_id' => $getProdiId('Industri'), 'room_number' => '10201', 'capacity' => 35, 'desc' => 'Analisis perancangan kerja.'],
            ['name' => 'Lab. Kimia Dasar', 'building_name' => 'Gedung 19b', 'prodi_id' => $getProdiId('Kimia'), 'room_number' => '19B01', 'capacity' => 40, 'desc' => 'Praktikum kimia dasar.'],

            // --- FTSP ---
            ['name' => 'Lab. Uji Bahan & Beton', 'building_name' => 'Gedung 12', 'prodi_id' => $getProdiId('Sipil'), 'room_number' => '12101', 'capacity' => 30, 'desc' => 'Uji material beton.'],
            ['name' => 'Lab. Mekanika Tanah', 'building_name' => 'Gedung 5', 'prodi_id' => $getProdiId('Sipil'), 'room_number' => '05101', 'capacity' => 25, 'desc' => 'Pengujian tanah dasar.'],

            ['name' => 'Lab. Survey & Pemetaan', 'building_name' => 'Gedung 18', 'prodi_id' => $getProdiId('Geodesi'), 'room_number' => '18201', 'capacity' => 50, 'desc' => 'Pengukuran tanah terestris.'],
            ['name' => 'Lab. Fotogrametri', 'building_name' => 'Gedung 18', 'prodi_id' => $getProdiId('Geodesi'), 'room_number' => '18202', 'capacity' => 40, 'desc' => 'Pemetaan udara & foto.'],

            ['name' => 'Lab. Mobilitas & Infrastruktur', 'building_name' => 'Gedung 3', 'prodi_id' => $getProdiId('Perencanaan Wilayah'), 'room_number' => '03201', 'capacity' => 35, 'desc' => 'Perencanaan transportasi.'],
            ['name' => 'Lab. Lingkungan', 'building_name' => 'Gedung 8', 'prodi_id' => $getProdiId('Lingkungan'), 'room_number' => '08101', 'capacity' => 30, 'desc' => 'Analisis kualitas air & limbah.'],

            // --- FAD (Kapasitas Studio biasanya lebih sedikit karena perlu meja gambar besar) ---
            ['name' => 'Studio Perancangan Arsitektur', 'building_name' => 'Gedung 17', 'prodi_id' => $getProdiId('Arsitektur'), 'room_number' => '17301', 'capacity' => 25, 'desc' => 'Studio gambar utama.'],
            ['name' => 'Lab. Fisika Bangunan', 'building_name' => 'Gedung 17', 'prodi_id' => $getProdiId('Arsitektur'), 'room_number' => '17302', 'capacity' => 30, 'desc' => 'Pencahayaan & akustik.'],

            ['name' => 'Lab. Fotografi & Audio Visual', 'building_name' => 'Gedung 1', 'prodi_id' => $getProdiId('Desain Komunikasi'), 'room_number' => '01201', 'capacity' => 20, 'desc' => 'Studio foto & video.'],
            ['name' => 'Lab. Prototyping', 'building_name' => 'Gedung 1', 'prodi_id' => $getProdiId('Desain Produk'), 'room_number' => '01105', 'capacity' => 25, 'desc' => 'Pembuatan prototype produk.'],
            ['name' => 'Lab. Material Interior', 'building_name' => 'Gedung 1', 'prodi_id' => $getProdiId('Desain Interior'), 'room_number' => '01301', 'capacity' => 30, 'desc' => 'Sampel material interior.'],

            // --- UMUM (Kapasitas Besar) ---
            ['name' => 'Bale Dayang Sumbi (GSG)', 'building_name' => 'Gedung Serba Guna', 'prodi_id' => null, 'room_number' => 'GSG-01', 'capacity' => 800, 'desc' => 'Gedung untuk seminar & wisuda.'],
            ['name' => 'Perpustakaan Pusat', 'building_name' => 'Gedung 9', 'prodi_id' => null, 'room_number' => 'LIB-01', 'capacity' => 200, 'desc' => 'Ruang baca dan diskusi.'],
        ];

        // 5. INSERT DATA
        foreach ($rooms as $room) {
            // Logika Mapping Koordinat
            $latitude = $defaultLat;
            $longitude = $defaultLong;

            // Cek koordinat berdasarkan nama gedung
            foreach ($coordinates as $key => $coord) {
                if (stripos($room['building_name'], $key) !== false) {
                    $latitude = $coord['lat'];
                    $longitude = $coord['long'];
                    break;
                }
                
                // Mapping manual khusus
                if ($room['building_name'] == 'Gedung Serba Guna' && $key == 'GSG') {
                    $latitude = $coord['lat']; $longitude = $coord['long']; break;
                }
                if ($room['building_name'] == 'Gedung 6' && $key == 'Gedung 11') {
                    $latitude = $coord['lat']; $longitude = $coord['long']; break;
                }
                if ($room['building_name'] == 'Gedung 5' && $key == 'Gedung 12') {
                    $latitude = $coord['lat']; $longitude = $coord['long']; break;
                }
            }

            DB::table('labs')->insert([
                'name'          => $room['name'],
                'building_name' => $room['building_name'],
                'room_number'   => $room['room_number'] ?? null,
                'description'   => $room['desc'],
                'prodi_id'      => $room['prodi_id'],
                'latitude'      => (string)$latitude,
                'longitude'     => (string)$longitude,
                // PERBAIKAN DI SINI: Menggunakan kapasitas spesifik dari array, default random jika lupa
                'capacity'      => $room['capacity'] ?? rand(30, 50),
                'status'        => 'available',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }
    }
}