<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReservationNotification extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['database']; // Menyimpan notifikasi di database
    }

    public function toArray($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'student_name'   => $this->reservation->user->name,
            'asset_name'     => $this->reservation->asset->name,
            'lab_name'       => $this->reservation->asset->lab->name,
            'message'        => "Peminjaman baru: " . $this->reservation->asset->name . " oleh " . $this->reservation->user->name,
        ];
    }
}