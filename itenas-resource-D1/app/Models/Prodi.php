<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;
    protected $guarded = ['id']; // Izinkan semua kolom diisi

    public function labs()
    {
        return $this->hasMany(Lab::class);
    }
}