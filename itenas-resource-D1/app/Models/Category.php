<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi (mass assignment)
    protected $guarded = ['id'];

    /**
     * Relasi: Satu Kategori punya banyak Aset
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}