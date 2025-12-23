<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Mail\ReservationCreatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReservationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reservation;

    /**
     * Create a new job instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Kirim email ke Admin (Hardcode email laboran untuk demo)
        Mail::to('laboran_if@itenas.ac.id')->send(new ReservationCreatedMail($this->reservation));
    }
}