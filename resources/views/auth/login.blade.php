@extends('layouts.app')

@section('content')
    <h1>Sign In</h1>

    <div class="card">
        <form method="POST" action="{{ route('login') }}" class="form-grid">
            @csrf
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
            <label>
                <input type="checkbox" name="remember" value="1">
                Remember me
            </label>
            <button type="submit">Login</button>
        </form>
    </div>
@endsection
