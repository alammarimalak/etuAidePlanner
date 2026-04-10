@extends('layouts.app')

@section('content')
    <h1>Settings</h1>

    <div class="card">
        <form method="POST" action="{{ route('settings.update') }}" class="form-grid">
            @csrf
            @method('PATCH')

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

            <div>
                <label>Current Password</label>
                <input type="password" name="current_password" autocomplete="current-password">
                @error('current_password')
                    <div class="muted">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label>New Password</label>
                <input type="password" name="password" autocomplete="new-password">
                @error('password')
                    <div class="muted">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label>Confirm New Password</label>
                <input type="password" name="password_confirmation" autocomplete="new-password">
            </div>

            <div class="actions">
                <button type="submit">Save Settings</button>
            </div>
        </form>
    </div>
@endsection
