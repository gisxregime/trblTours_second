<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignupVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $greeting;

    private string $messageLine;

    public function __construct(
        public string $verificationUrl,
        public string $email,
        public string $otp,
        public string $role = 'tourist', // 'tourist' or 'guide'
    ) {
        $this->setDynamicContent();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Email Verification Code – TrblTours',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.verification',
            with: [
                'verificationUrl' => $this->verificationUrl,
                'email' => $this->email,
                'otp' => $this->otp,
                'greeting' => $this->greeting,
                'messageLine' => $this->messageLine,
            ],
        );
    }

    private function setDynamicContent(): void
    {
        // Set greeting based on role
        $this->greeting = $this->role === 'guide'
            ? 'Hello Tour Guide,'
            : 'Hello Traveler,';

        // Set message for signup
        $this->messageLine = 'Thank you for signing up for TrblTours! To verify your email address, please use the verification code below:';
    }
}
