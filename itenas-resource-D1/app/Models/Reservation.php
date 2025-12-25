<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations'; // Opsional, tapi bagus untuk memastikan nama tabel

    // PERBAIKAN: Menambahkan kolom-kolom yang tadi kita pakai di Seeder & Controller
    protected $fillable = [
        'user_id',
        'asset_id',         // ID Aset (Jika tipe = asset)
        'lab_id',           // ID Lab (Jika tipe = room / booking)
        'transaction_code',
        'start_time',
        'end_time',
        'status',           // pending, approved, borrowed, returned, rejected
        'type',             // asset / room / booking
        'purpose',          // Keperluan peminjaman
        
        // Bagian Denda & Penolakan
        'rejection_note',   // Alasan penolakan
        'penalty',          // Nominal denda (sesuai controller)
        'penalty_amount',   // Cadangan jika database pakai nama ini
        'penalty_status',   // paid / unpaid
        
        // Bagian Pembayaran
        'payment_status',   // paid / unpaid
        'payment_method',   // Cash / Transfer / QRIS
        'proposal_file',    // Jika ada upload proposal
    ];

    // Casting agar otomatis jadi Carbon Object (Bisa format tanggal)
    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // === RELASI ===

    // 1. Ke User (Peminjam)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. Ke Asset (Jika peminjaman barang)
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    // 3. Ke Lab (INI WAJIB ADA untuk perbaikan Controller tadi)
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    // 4. Relasi Item (Jika sistem keranjang)
    public function reservationItems()
    {
        return $this->hasMany(ReservationItem::class);
    }

    // 5. Pembayaran (Opsional jika dipisah tabel)
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}