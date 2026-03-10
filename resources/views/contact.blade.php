@extends('layouts.app')

@section('content')
    <div class="card">
        <h1 class="section-title">Contact Us</h1>
        <p class="muted">We love hearing from students, teachers, and parents. Send us a note and we will reply soon.</p>
    </div>

    <div class="grid grid-3">
        <div class="card">
            <h3>Email</h3>
            <p class="muted">support@etuaide.com</p>
        </div>
        <div class="card">
            <h3>Campus hours</h3>
            <p class="muted">Mon to Fri · 9:00 to 18:00</p>
        </div>
        <div class="card">
            <h3>Community</h3>
            <p class="muted">Ask us about partnerships or onboarding.</p>
        </div>
    </div>

    <div class="card">
        <h3>Send a message</h3>
        <form method="POST" action="{{ route('contact.submit') }}" class="form-grid">
            @csrf
            <div>
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="muted">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="muted">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label>Message</label>
                <textarea name="message" rows="4" required>{{ old('message') }}</textarea>
                @error('message')
                    <div class="muted">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
@endsection
