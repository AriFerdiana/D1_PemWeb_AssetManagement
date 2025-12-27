<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category_id',
        'lab_id', // Ini kunci utama untuk tahu aset ini milik prodi mana
        'stock',
        'image',
        'status',
        'description'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    /**
     * Shortcut untuk mendapatkan prodi aset melalui Lab
     */
    public function getProdiAttribute()
    {
        return $this->lab->prodi ?? null;
    }
}