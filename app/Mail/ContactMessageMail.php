<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $messageBody,
    ) {
    }

    public function build(): static
    {
        return $this
            ->replyTo($this->senderEmail, $this->senderName)
            ->subject('New contact message from ' . $this->senderName)
            ->view('emails.contact-message');
    }
}
