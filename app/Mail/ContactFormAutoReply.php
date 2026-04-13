<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormAutoReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $messageBody,
        public string $supportEmail,
    ) {
    }

    public function build(): static
    {
        return $this
            ->replyTo($this->supportEmail, 'EtuAide')
            ->subject('We received your EtuAide message')
            ->view('emails.contact-form-auto-reply');
    }
}
