@extends('layouts.app')

@section('body_class', 'auth-page')

@section('content')
    <style>
        :root {
            --etuaide-surface: #ffffff;
            --etuaide-border: #d7dfe8;
            --etuaide-text: #1f2a47;
            --etuaide-muted: #5f6d8f;
            --etuaide-primary: #2156f5;
            --etuaide-primary-hover: #183dc4;
            --etuaide-accent: #00b5ad;
            --etuaide-shadow: 0 18px 45px rgba(58, 80, 126, 0.12);
        }

        body.theme-dark {
            --etuaide-surface: rgba(10, 19, 41, 0.94);
            --etuaide-border: rgba(139, 119, 255, 0.2);
            --etuaide-text: #eef2ff;
            --etuaide-muted: #9aa8ca;
            --etuaide-shadow: 0 22px 48px rgba(0, 0, 0, 0.34);
        }

        .login-container {
            min-height: calc(100vh - 24px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px 48px;
            background: #eef4fb;
        }

        body.theme-dark .login-container {
            background:
                radial-gradient(circle at top left, rgba(33, 86, 245, 0.16), transparent 32%),
                radial-gradient(circle at bottom right, rgba(93, 62, 240, 0.18), transparent 28%),
                #08101f;
        }

        .login-card {
            width: 100%;
            max-width: 460px;
            background: var(--etuaide-surface);
            border-radius: 28px;
            box-shadow: var(--etuaide-shadow);
            padding: 34px;
            border: 1px solid rgba(33, 86, 245, 0.08);
        }

        body.theme-dark .login-card {
            border-color: var(--etuaide-border);
            backdrop-filter: blur(14px);
        }

        .brand-row {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 28px;
        }

        .brand-logo {
            width: min(100%, 103px);
            height: auto;
            display: block;
        }

        .brand-logo--dark {
            display: none;
        }

        body.theme-dark .brand-logo--light {
            display: none;
        }

        body.theme-dark .brand-logo--dark {
            display: block;
        }

        .page-heading {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--etuaide-text);
            text-align: center;
            margin-bottom: 8px;
        }

        .page-copy {
            margin: 0;
            font-size: 0.96rem;
            color: var(--etuaide-muted);
            text-align: center;
            margin-bottom: 26px;
        }

        .form-grid {
            display: grid;
            gap: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 0.82rem;
            letter-spacing: 0.01em;
            font-weight: 700;
            color: var(--etuaide-muted);
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid var(--etuaide-border);
            background: #f8fbff;
            font-size: 0.98rem;
            color: var(--etuaide-text);
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--etuaide-primary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(33, 86, 245, 0.06);
        }

        body.theme-dark .form-group input:focus {
            background: rgba(255, 255, 255, 0.08);
        }

        .muted {
            color: #d14343;
            font-size: 0.85rem;
            margin-top: 8px;
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            font-size: 0.92rem;
            color: var(--etuaide-muted);
        }

        .options-row label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--etuaide-text);
        }

        .options-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--etuaide-primary);
        }

        .forget-link {
            color: var(--etuaide-primary);
            text-decoration: none;
            font-weight: 600;
        }

        .forget-link:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 14px 18px;
            border: none;
            border-radius: 16px;
            background: var(--etuaide-primary);
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .submit-btn:hover {
            background: var(--etuaide-primary-hover);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 30px 0;
            color: var(--etuaide-muted);
            font-size: 0.87rem;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--etuaide-border);
        }

        .social-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 16px;
            border: 1px solid var(--etuaide-border);
            background: #ffffff;
            color: var(--etuaide-text);
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
            transition: border-color 0.2s ease, transform 0.15s ease;
        }

        body.theme-dark .social-btn {
            background: rgba(255, 255, 255, 0.04);
            border-color: var(--etuaide-border);
            color: var(--etuaide-text);
        }

        .social-btn:hover {
            border-color: #b7c3ea;
            transform: translateY(-1px);
        }

        body.theme-dark .social-btn:hover {
            border-color: rgba(139, 119, 255, 0.34);
        }

        .social-btn.google {
            color: #d44638;
        }

        .social-btn.microsoft {
            color: #0969da;
        }

        .signup-note {
            margin-top: 24px;
            text-align: center;
            color: var(--etuaide-muted);
            font-size: 0.95rem;
        }

        .signup-note a {
            color: var(--etuaide-primary);
            font-weight: 700;
            text-decoration: none;
        }

        .signup-note a:hover {
            text-decoration: underline;
        }

        @media (max-width: 575.98px) {
            .login-container {
                min-height: auto;
                padding: 24px 0 32px;
            }

            .login-card {
                padding: 24px 18px;
                border-radius: 22px;
            }

            .page-heading {
                font-size: 1.7rem;
            }

            .social-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="login-container">
        <div class="login-card">
            <div class="brand-row">
                <img class="brand-logo brand-logo--light" src="{{ asset('storage/EtuAide_lightmode.png') }}" alt="EtuAide">
                <img class="brand-logo brand-logo--dark" src="{{ asset('storage/EtuAide_darkmode.png') }}" alt="EtuAide">
            </div>

            <h1 class="page-heading">Create your EtuAide account</h1>
            <p class="page-copy">Join thousands of students organizing their academic life with EtuAide.</p>

            <form method="POST" action="{{ route('register') }}" class="form-grid">
                @csrf
                <div class="form-group">
                    <label for="name">Full name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <div class="muted">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <div class="muted">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="muted">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>

                <button type="submit" class="submit-btn">Create account</button>
            </form>

            <p class="signup-note">Already have an EtuAide account? <a href="{{ route('login') }}">Sign in</a></p>
        </div>
    </div>
@endsection
