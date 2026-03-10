<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $user = $this->currentUser();

        return view('settings.edit', [
            'currentUser' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'timezone' => ['nullable', 'string', 'max:100'],
            'theme' => ['required', 'in:light,dark'],
            'palette' => ['nullable', 'in:lavender,grape,midnight'],
            'notifications_enabled' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'timezone' => $data['timezone'] ?: null,
            'theme' => $data['theme'],
            'palette' => $data['palette'] ?? null,
            'notifications_enabled' => $request->boolean('notifications_enabled'),
        ]);

        return redirect()->route('settings.edit')->with('status', 'Settings updated.');
    }
}
