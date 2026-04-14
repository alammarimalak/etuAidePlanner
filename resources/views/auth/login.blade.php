@extends('layouts.app')

@section('content')
    <style>
        :root {
        --ds-background-neutral: #F4F5F7;
        --ds-text: #172B4D;
        --ds-text-subtle: #6B778C;
        --ds-link: #0052CC;
        --ds-border: #DFE1E6;
        --ds-primary: #0052CC;
        --ds-primary-hover: #0747A6;
    }

    body {
        background-color: var(--ds-background-neutral);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
        color: var(--ds-text);
    }

    .login-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
    }

    .card {
        background: white;
        border-radius: 3px;
        box-shadow: 0 10px 10px -5px rgba(0,0,0,0.1);
        padding: 40px;
        width: 100%;
        max-width: 400px;
        box-sizing: border-box;
    }

    h1 {
        font-size: 20px;
        font-weight: 500;
        color: var(--ds-text-subtle);
        text-align: center;
        margin-bottom: 30px;
    }

    .form-grid > div {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--ds-text-subtle);
        margin-bottom: 4px;
    }

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid var(--ds-border);
        border-radius: 3px;
        background-color: #FAFBFC;
        font-size: 14px;
        transition: background-color 0.2s, border-color 0.2s;
        box-sizing: border-box;
    }

    input:focus {
        outline: none;
        background-color: white;
        border-color: #4C9AFF;
    }

    .muted {
        color: #DE350B;
        font-size: 12px;
        margin-top: 5px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: var(--ds-text);
        cursor: pointer;
    }

    .remember-me input {
        margin-right: 8px;
    }

    .actions {
        margin-top: 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    button[type="submit"] {
        background-color: var(--ds-primary);
        color: white;
        border: none;
        border-radius: 3px;
        padding: 10px 20px;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.1s ease-out;
    }

    button[type="submit"]:hover {
        background-color: var(--ds-primary-hover);
    }

    .btn.secondary {
        text-align: center;
        text-decoration: none;
        color: var(--ds-link);
        font-size: 14px;
    }

    .btn.secondary:hover {
        text-decoration: underline;
    }
    </style>
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
            <div class="actions">
                <button type="submit">Login</button>
                <a class="btn secondary" href="{{ route('register') }}">Need an account?</a>
            </div>
        </form>
    </div>
@endsection
