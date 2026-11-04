<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $recipientName;
    public int $expiresInMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, ?string $recipientName = null, int $expiresInMinutes = 10)
    {
        $this->otp = $otp;
        $this->recipientName = $recipientName ?: 'Noksha Member';
        $this->expiresInMinutes = $expiresInMinutes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                env('MAIL_FROM_ADDRESS', config('mail.from.address', 'noksha.official1@gmail.com')),
                env('MAIL_FROM_NAME', config('mail.from.name', 'Noksha Security'))
            ),
            subject: "Noksha Security — 6-Digit Email Verification Code ({$this->otp})",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-otp',
            with: [
                'otp' => $this->otp,
                'recipientName' => $this->recipientName,
                'expiresInMinutes' => $this->expiresInMinutes,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
