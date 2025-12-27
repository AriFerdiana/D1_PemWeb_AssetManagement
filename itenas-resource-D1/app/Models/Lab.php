<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lab extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'prodi_id',
        'building_name', // Diperbaiki dari 'location'
        'capacity',
        'description',
        'latitude',
        'longitude'
    ];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'lab_id');
    }
}