<?php

namespace App\Mail;

use App\Models\WaitlistEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WaitlistInviteMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public WaitlistEntry $entry;

    /**
     * Create a new message instance.
     */
    public function __construct(WaitlistEntry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $patientName = optional($this->entry->patient)->child_name;
        return $this->subject('Invitation to Schedule an Appointment')
            ->view('emails.waitlist_invite')
            ->with([
                'entry' => $this->entry,
                'patientName' => $patientName,
            ]);
    }
}