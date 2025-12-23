<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // Kita ubah ke $fillable agar lebih aman dan jelas kolom apa saja yang ada
    protected $fillable = [
        'user_id',
        'asset_id',         // ID Aset (Jika sistem 1 reservasi = 1 aset)
        'lab_id',           // ID Lab (Jika booking ruangan)
        'transaction_code', // Kode TRX
        'start_time',
        'end_time',
        'status',           // pending, approved, borrowed, returned, rejected
        'type',             // asset / room
        
        // === KOLOM BARU (DENDA) ===
        'penalty_amount',
        'penalty_status',
    ];

    // PENTING: Casting Tanggal agar bisa diformat ($item->start_time->format('d M Y'))
    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    // === RELASI ===

    // 1. Ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 2. Ke Asset (PENTING: Dashboard memanggil $trx->asset->name)
    // Relasi ini dipakai jika 1 Reservasi hanya untuk 1 Barang
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    // 3. Ke Reservation Items (Dipakai jika 1 Reservasi = Banyak Barang)
    public function reservationItems()
    {
        return $this->hasMany(ReservationItem::class);
    }

    // 4. Ke Lab (Dipakai jika type = 'room')
    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    // 5. Ke Payment (Jika ada)
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}