<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'nim',          // WAJIB: Agar NIM Mahasiswa tersimpan
        'prodi_id',     // WAJIB: Agar relasi ke Laboran jalan
        'prodi',        // Opsional (string fallback)
        'phone',        // Tambahan: Berguna untuk kontak darurat peminjaman
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke Prodi (Mahasiswa milik satu Prodi)
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
}