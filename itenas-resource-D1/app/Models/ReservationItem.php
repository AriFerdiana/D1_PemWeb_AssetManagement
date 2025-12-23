<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Item ini milik Transaksi mana
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    // Relasi: Item ini merujuk ke Aset mana
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}