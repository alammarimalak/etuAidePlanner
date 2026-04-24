<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminStudentEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderEmail,
        public string $recipientName,
        public string $subjectLine,
        public string $messageBody,
    ) {
    }

    public function build(): static
    {
        return $this
            ->from($this->senderEmail)
            ->replyTo($this->senderEmail)
            ->subject($this->subjectLine)
            ->view('emails.admin-student-email');
    }
}
