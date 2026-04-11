<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

class HomeController extends Controller
{
    private const CONTACT_RECIPIENT = 'alammarimalak17@gmail.com';

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

        try {
            Mail::to(self::CONTACT_RECIPIENT)->send(new ContactFormMessage(
                senderName: (string) $request->input('name'),
                senderEmail: (string) $request->input('email'),
                messageBody: (string) $request->input('message'),
            ));
        } catch (Throwable $exception) {
            return redirect()
                ->to(route('home') . '#contact')
                ->withErrors([
                    'message' => 'Your message could not be sent right now. Please try again in a moment.',
                ])
                ->withInput();
        }

        return redirect()
            ->to(route('home') . '#contact')
            ->with('status', 'Thanks! Your message is on its way.');
    }
}
