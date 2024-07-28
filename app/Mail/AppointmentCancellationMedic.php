<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancellationMedic extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment->load('doctor', 'user', 'consult');
    }

    public function build()
    {
        return $this->subject('Anularea programării')
            ->view('emails.appointment_cancellation_medic');
    }
}
