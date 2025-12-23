<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    // Kita ganti $guarded menjadi $fillable agar lebih spesifik kolom mana yang boleh diisi
    protected $fillable = [
        'name',
        'code',
        'building_name',
        'description',
        'latitude',   // <--- Kolom Baru untuk Peta
        'longitude'   // <--- Kolom Baru untuk Peta
    ];

    // Relasi ke Prodi (Opsional, jika ada tabel prodis)
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    // Relasi ke Aset (Lab ini punya banyak aset)
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}