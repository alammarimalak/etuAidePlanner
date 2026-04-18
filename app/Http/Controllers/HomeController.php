<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormAutoReply;
use App\Mail\ContactFormMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function about()
    {
        return redirect()->to(route('home') . '#about');
    }

    public function faq()
    {
        return redirect()->to(route('home') . '#faq');
    }

    public function contact()
    {
        return redirect()->to(route('home') . '#contact');
    }

    public function submitContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->to(route('home') . '#contact')
                ->withErrors($validator)
                ->withInput();
        }

        $contactData = $validator->validated();
        $contactRecipient = (string) config('mail.contact.recipient');

        try {
            Mail::to($contactRecipient)->send(new ContactFormMessage(
                senderName: (string) $contactData['name'],
                senderEmail: (string) $contactData['email'],
                messageBody: (string) $contactData['message'],
            ));

            Mail::to((string) $contactData['email'])->send(new ContactFormAutoReply(
                senderName: (string) $contactData['name'],
                senderEmail: (string) $contactData['email'],
                messageBody: (string) $contactData['message'],
                supportEmail: $contactRecipient,
            ));
        } catch (Throwable $exception) {
            Log::error('Contact form email failed to send.', [
                'sender_email' => (string) $contactData['email'],
                'recipient_email' => $contactRecipient,
                'error' => $exception->getMessage(),
            ]);

            return redirect()
                ->to(route('home') . '#contact')
                ->with('contact_error', 'Your message could not be sent right now. Please try again in a moment.')
                ->withInput();
        }

        return redirect()
            ->to(route('home') . '#contact')
            ->with('contact_status', 'Thanks! Your message is on its way. Please check your inbox for our confirmation email.');
    }
}
