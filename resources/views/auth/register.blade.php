@extends('layouts.app')

@section('content')
    <h1>Sign Up</h1>

    <div class="card">
        <form method="POST" action="{{ route('register') }}" class="form-grid">
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
                <label>Password</label>
                <input type="password" name="password" required>
                @error('password')
                    <div class="muted">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <div class="actions">
                <button type="submit">Create Account</button>
                <a class="btn secondary" href="{{ route('login') }}">Already registered?</a>
            </div>
        </form>
    </div>
@endsection
