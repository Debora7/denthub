<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReview extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->subject('Apreciem feedbackul tău')
            ->view('emails.appointment_review_request')
            ->with([
                'appointment' => $this->appointment,
                'reviewUrl' => route('consult.client.appointment.index'),
            ]);
    }
}
