<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailChangeVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $newEmail;
    public string $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code, string $newEmail, string $userName)
    {
        $this->code = $code;
        $this->newEmail = $newEmail;
        $this->userName = $userName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SyncBin - Email Change Verification Code: ' . $this->code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.email-change-code',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
