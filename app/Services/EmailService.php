<?php

namespace App\Services;

use App\Mail\AppointmentStatusMail;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use App\Mail\WaitlistInviteMail;
use App\Models\WaitlistEntry;

class EmailService
{
    /**
     * Send appointment status update email
     *
     * @param Appointment $appointment
     * @return array
     */
    public function sendAppointmentStatusEmail(Appointment $appointment): array
    {
        $recipient = optional($appointment->user)->email;
        
        if (!$recipient) {
            return [
                'success' => false,
                'message' => "No email found for appointment #{$appointment->id}."
            ];
        }

        try {
            Mail::to($recipient)->send(new AppointmentStatusMail($appointment));
            return [
                'success' => true,
                'message' => "Email sent to {$recipient} for appointment #{$appointment->id}."
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => "Failed to send email: " . $e->getMessage()
            ];
        }
    }

    /**
     * Send waitlist invitation email to the patient's guardian.
     *
     * @param WaitlistEntry $entry
     * @return array
     */
    public function sendWaitlistInviteEmail(WaitlistEntry $entry): array
    {
        // Ensure guardian relationship is loaded
        $entry->load('patient.guardian');
        $recipient = optional(optional($entry->patient)->guardian)->email;

        if (!$recipient) {
            return [
                'success' => false,
                'message' => 'No guardian email on file for this patient.'
            ];
        }

        try {
            // Queue the mailable to avoid blocking the request
            Mail::to($recipient)->queue(new WaitlistInviteMail($entry));
            return [
                'success' => true,
                'message' => "Invitation email queued for {$recipient}."
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Failed to queue invite email. Please try again later.'
            ];
        }
    }
}