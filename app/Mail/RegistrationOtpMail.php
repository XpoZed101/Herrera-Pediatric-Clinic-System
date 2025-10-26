<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public int $ttlMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code, int $ttlMinutes = 10)
    {
        $this->code = $code;
        $this->ttlMinutes = $ttlMinutes;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Your verification code')
            ->view('emails.registration_otp')
            ->with([
                'code' => $this->code,
                'ttlMinutes' => $this->ttlMinutes,
            ]);
    }
}