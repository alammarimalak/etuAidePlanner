<?php

namespace App\Http\Controllers;

use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function edit()
    {
        $user = $this->currentUser();

        return view('settings.edit', [
            'currentUser' => $user,
            'timezones' => Timezone::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', Rule::exists('timezones', 'name')],
            'theme' => ['required', 'in:light,dark'],
            'notifications_enabled' => ['nullable', 'boolean'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updates = [
            'name' => $data['name'],
            'timezone' => $data['timezone'] ?: null,
            'theme' => $data['theme'],
            'notifications_enabled' => $request->boolean('notifications_enabled'),
        ];

        if (!empty($data['password'])) {
            $updates['password'] = $data['password'];
        }

        $user->update($updates);

        return redirect()->route('settings.edit')->with('status', 'Settings updated.');
    }
}
