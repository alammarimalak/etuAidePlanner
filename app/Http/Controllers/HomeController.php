<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        return redirect()
            ->to(route('home') . '#contact')
            ->with('status', 'Thanks! Your message is on its way.');
    }
}
