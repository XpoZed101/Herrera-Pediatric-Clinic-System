<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment)
    {
    }

    public function build(): self
    {
        $status = $this->appointment->status ?? 'requested';
        $subjectMap = [
            'requested' => 'We received your appointment request',
            'scheduled' => 'Your appointment is scheduled',
            'completed' => 'Thanks for visiting – your appointment is completed',
            'cancelled' => 'Your appointment has been cancelled',
        ];
        $subject = $subjectMap[$status] ?? 'Appointment update';

        return $this->subject($subject)
            ->view('emails.appointment_status')
            ->with([
                'appointment' => $this->appointment,
                'status' => $status,
            ]);
    }
}