<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment->load('doctor', 'user', 'consult');
    }

    public function build()
    {
        return $this->subject('Appointment Confirmation')
            ->view('emails.appointment_confirmation');
    }
}
