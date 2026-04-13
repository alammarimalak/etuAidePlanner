<?php

namespace Tests\Feature;

use App\Mail\ContactFormAutoReply;
use App\Mail\ContactFormMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_sends_email_to_company_and_confirmation_to_sender(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.submit'), [
            'name' => 'Malak',
            'email' => 'malak@example.com',
            'message' => 'Hello from the contact form.',
        ]);

        $response->assertRedirect(route('home') . '#contact');

        Mail::assertSent(ContactFormMessage::class, function (ContactFormMessage $mail) {
            $mail->build();

            return $mail->senderName === 'Malak'
                && $mail->senderEmail === 'malak@example.com'
                && $mail->messageBody === 'Hello from the contact form.'
                && $mail->hasFrom('malak@example.com', 'Malak')
                && $mail->hasTo('alammarimalak17@gmail.com');
        });

        Mail::assertSent(ContactFormAutoReply::class, function (ContactFormAutoReply $mail) {
            $mail->build();

            return $mail->senderName === 'Malak'
                && $mail->senderEmail === 'malak@example.com'
                && $mail->messageBody === 'Hello from the contact form.'
                && $mail->supportEmail === 'alammarimalak17@gmail.com'
                && $mail->hasTo('malak@example.com')
                && $mail->hasReplyTo('alammarimalak17@gmail.com', 'EtuAide');
        });
    }
}
