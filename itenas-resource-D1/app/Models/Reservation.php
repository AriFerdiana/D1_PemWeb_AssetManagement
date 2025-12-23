<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // Aman! Ini artinya semua kolom boleh diisi (termasuk type & lab_id) kecuali ID
    protected $guarded = ['id'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Reservation Items (Dipakai jika type = 'asset')
    public function reservationItems()
    {
        return $this->hasMany(ReservationItem::class);
    }

    // Relasi ke Payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // === TAMBAHAN BARU ===
    // Relasi ke Lab (Dipakai jika type = 'room')
    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
}