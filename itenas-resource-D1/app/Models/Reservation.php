<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'user_id',
        'asset_id',
        'lab_id',
        'transaction_code',
        'start_time',
        'end_time',
        'status',
        'type',
        'purpose',
        'rejection_note',
        'penalty',
        'penalty_status',
        'payment_status',
        'payment_method',
        'proposal_file',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'penalty'    => 'integer',
    ];

    // =========================================================================
    // RELASI
    // =========================================================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    public function reservationItems()
    {
        return $this->hasMany(ReservationItem::class, 'reservation_id');
    }

    // =========================================================================
    // HELPER FUNCTIONS
    // =========================================================================

    /**
     * Mengecek apakah peminjaman sudah melewati batas waktu (Overdue)
     */
    public function isOverdue()
    {
        return $this->status === 'borrowed' && now()->gt($this->end_time);
    }

    /**
     * Mendapatkan label warna status untuk UI
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending'  => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'borrowed' => 'bg-purple-100 text-purple-800',
            'returned' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default    => 'bg-gray-100 text-gray-800',
        };
    }
}