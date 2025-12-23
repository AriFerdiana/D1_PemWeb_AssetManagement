<?php

namespace App\Events;

use App\Models\Reservation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewReservationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Reservation $reservation)
    {
        // Pesan yang akan muncul di notifikasi
        $this->message = "Booking Baru! {$reservation->user->name} meminjam {$reservation->asset->name}";
    }

    public function broadcastOn()
    {
        // Nama channel yang didengarkan
        return [new Channel('itenas-channel')];
    }

    public function broadcastAs()
    {
        // Nama event yang didengarkan
        return 'new-booking';
    }
}