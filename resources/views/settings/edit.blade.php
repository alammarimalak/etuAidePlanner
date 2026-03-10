@extends('layouts.app')

@section('content')
    <h1>Settings</h1>

    <div class="card">
        <form method="POST" action="{{ route('settings.update') }}" class="form-grid">
            @csrf
            @method('PATCH')

            <div>
                <label>Timezone</label>
                <input type="text" name="timezone" value="{{ old('timezone', $currentUser->timezone) }}" placeholder="Africa/Casablanca">
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
                <label>Palette</label>
                <select name="palette">
                    <option value="">Default</option>
                    <option value="lavender" @selected(old('palette', $currentUser->palette) === 'lavender')>Lavender</option>
                    <option value="grape" @selected(old('palette', $currentUser->palette) === 'grape')>Grape</option>
                    <option value="midnight" @selected(old('palette', $currentUser->palette) === 'midnight')>Midnight</option>
                </select>
            </div>

            <div>
                <label>
                    <input type="checkbox" name="notifications_enabled" value="1" @checked(old('notifications_enabled', $currentUser->notifications_enabled))>
                    Enable notifications
                </label>
            </div>

            <div class="actions">
                <button type="submit">Save Settings</button>
                <a class="btn secondary" href="{{ route('dashboard') }}">Back to Dashboard</a>
            </div>
        </form>
    </div>
@endsection
