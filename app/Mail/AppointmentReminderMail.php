<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment)
    {
    }

    public function build(): self
    {
        return $this->subject('Appointment Reminder – 24 hours to go')
            ->view('emails.appointment_reminder')
            ->with([
                'appointment' => $this->appointment,
            ]);
    }
}