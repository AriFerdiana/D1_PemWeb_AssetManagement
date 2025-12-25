<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Lab;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil User ID (Kecuali Admin ID 1)
        $users = User::where('id', '!=', 1)->pluck('id')->toArray();
        if (empty($users)) $users = User::pluck('id')->toArray();

        // 2. Ambil Lab ID
        $labs = Lab::pluck('id')->toArray();

        if (empty($users) || empty($labs)) {
            $this->command->info('⚠️ Gagal: Pastikan tabel Users dan Labs sudah ada isinya!');
            return;
        }

        $data = [];
        $purposes = ['Praktikum Mandiri', 'Rapat Himpunan', 'Mengerjakan Skripsi', 'Workshop', 'Kegiatan Lomba'];

        // --- SKENARIO 1: SUDAH SELESAI (Approved) ---
        for ($i = 0; $i < 20; $i++) {
            $tglMulai = Carbon::now()->subDays(rand(5, 60))->setHour(rand(8, 14));
            
            $data[] = [
                'user_id'           => $users[array_rand($users)],
                'lab_id'            => $labs[array_rand($labs)],
                'transaction_code'  => 'TRX-' . strtoupper(Str::random(8)),
                'type'              => 'booking',
                'purpose'           => $purposes[array_rand($purposes)],
                'start_time'        => $tglMulai->format('Y-m-d H:i:s'),
                'end_time'          => $tglMulai->copy()->addHours(2)->format('Y-m-d H:i:s'),
                'status'            => 'approved',
                'rejection_note'    => null,
                'penalty_status'    => 'unpaid', // <--- KITA PAKAI 'unpaid' AGAR LOLOS VALIDASI
                'created_at'        => $tglMulai,
                'updated_at'        => $tglMulai,
            ];
        }

        // --- SKENARIO 2: PENDING (Pengajuan Baru) ---
        for ($i = 0; $i < 5; $i++) {
            $tglMulai = Carbon::now()->addDays(rand(1, 7))->setHour(rand(8, 14));
            
            $data[] = [
                'user_id'           => $users[array_rand($users)],
                'lab_id'            => $labs[array_rand($labs)],
                'transaction_code'  => 'TRX-' . strtoupper(Str::random(8)),
                'type'              => 'booking',
                'purpose'           => 'Pengajuan Peminjaman Lab',
                'start_time'        => $tglMulai->format('Y-m-d H:i:s'),
                'end_time'          => $tglMulai->copy()->addHours(3)->format('Y-m-d H:i:s'),
                'status'            => 'pending',
                'rejection_note'    => null,
                'penalty_status'    => 'unpaid', // Default database
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ];
        }

        // --- SKENARIO 3: DITOLAK (Rejected) ---
        for ($i = 0; $i < 5; $i++) {
            $tglMulai = Carbon::now()->subDays(rand(2, 10));
            
            $data[] = [
                'user_id'           => $users[array_rand($users)],
                'lab_id'            => $labs[array_rand($labs)],
                'transaction_code'  => 'TRX-' . strtoupper(Str::random(8)),
                'type'              => 'booking',
                'purpose'           => 'Ingin main game',
                'start_time'        => $tglMulai->format('Y-m-d H:i:s'),
                'end_time'          => $tglMulai->copy()->addHours(2)->format('Y-m-d H:i:s'),
                'status'            => 'rejected',
                'rejection_note'    => 'Tujuan tidak sesuai ketentuan akademik',
                'penalty_status'    => 'unpaid', // Default database
                'created_at'        => $tglMulai,
                'updated_at'        => $tglMulai,
            ];
        }

        DB::table('reservations')->insert($data);
    }
}