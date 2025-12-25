<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi: Satu Prodi punya banyak Lab
     */
    public function labs()
    {
        return $this->hasMany(Lab::class);
    }

    /**
     * Relasi: Satu Prodi punya banyak User (Mahasiswa/Laboran)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}