<?php

namespace Tests\Feature;

use App\Mail\ContactFormMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_sends_email_to_configured_gmail_address(): void
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
    }
}
