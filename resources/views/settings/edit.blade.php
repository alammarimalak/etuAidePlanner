@extends('layouts.app')

@push('styles')
    <style>
        .settings-layout {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .settings-panel {
            display: grid;
            gap: 14px;
            padding: 22px;
            border-radius: 22px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.46);
        }

        .settings-panel > div {
            min-width: 0;
        }

        .settings-panel h2 {
            margin: 0;
            font-size: 1.05rem;
        }

        .settings-panel p {
            margin: 0;
        }

        .settings-layout .actions {
            grid-column: 1 / -1;
        }

        .settings-password-field {
            display: grid;
            grid-template-columns: minmax(140px, 180px) minmax(0, 1fr);
            gap: 16px;
            align-items: start;
            min-width: 0;
        }

        .settings-password-field label {
            margin-top: 12px;
            font-weight: 700;
        }

        .settings-password-input {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .settings-panel input,
        .settings-panel select {
            width: 100%;
            max-width: 100%;
            min-width: 0;
        }

        body.theme-dark .settings-panel {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(139, 119, 255, 0.18);
        }

        @media (max-width: 860px) {
            .settings-layout {
                grid-template-columns: 1fr;
            }

            .settings-password-field {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .settings-password-field label {
                margin-top: 0;
            }
        }
    </style>
@endpush

@section('content')
    <h1>Settings</h1>

    <div class="card">
        <form method="POST" action="{{ route('settings.update') }}" class="settings-layout">
            @csrf
            @method('PATCH')

            <div class="settings-panel">
                <div>
                    <h2>Profile Settings</h2>
                    <p class="muted">Update your name, timezone, appearance, and notification preference.</p>
                </div>

                <div>
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name', $currentUser->name) }}" required>
                    @error('name')
                        <div class="muted">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label>Timezone</label>
                    <select name="timezone">
                        <option value="">Use app default</option>
                        @foreach ($timezones as $timezone)
                            <option value="{{ $timezone->name }}" @selected(old('timezone', $currentUser->timezone) === $timezone->name)>{{ $timezone->name }}</option>
                        @endforeach
                    </select>
                    @error('timezone')
                        <div class="muted">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label>Theme</label>
                    <select name="theme">
                        <option value="light" @selected(old('theme', $currentUser->theme) === 'light')>Light</option>
                        <option value="dark" @selected(old('theme', $currentUser->theme) === 'dark')>Dark</option>
                    </select>
                </div>

                <div>
                    <label>
                        <input type="checkbox" name="notifications_enabled" value="1" @checked(old('notifications_enabled', $currentUser->notifications_enabled))>
                        Enable notifications
                    </label>
                </div>
            </div>

            <div class="settings-panel">
                <div>
                    <h2>Password</h2>
                    <p class="muted">Use these fields only when you want to change your password.</p>
                </div>

                <div class="settings-password-field">
                    <label for="current_password">Current Password</label>
                    <div class="settings-password-input">
                        <input id="current_password" type="password" name="current_password" autocomplete="current-password">
                        @error('current_password')
                            <div class="muted">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="settings-password-field">
                    <label for="password">New Password</label>
                    <div class="settings-password-input">
                        <input id="password" type="password" name="password" autocomplete="new-password">
                        @error('password')
                            <div class="muted">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="settings-password-field">
                    <label for="password_confirmation">Confirm New Password</label>
                    <div class="settings-password-input">
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="actions">
                <button type="submit">Save Settings</button>
            </div>
        </form>
    </div>
@endsection
