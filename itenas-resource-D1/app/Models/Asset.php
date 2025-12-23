<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi (Mass Assignment)
    protected $guarded = ['id'];

    // === RELASI YANG SEBELUMNYA SUDAH ADA ===
    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    // === INI YANG HILANG & BIKIN ERROR ===
    // Menghubungkan Aset ke Kategori (category_id)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // === RELASI TAMBAHAN (Opsional untuk ke depannya) ===
    public function reservationItems()
    {
        return $this->hasMany(ReservationItem::class);
    }
}