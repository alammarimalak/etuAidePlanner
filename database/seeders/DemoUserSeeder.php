<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        User::updateOrCreate(
            ['email' => 'admin@etuaide.test'],
            [
                'name' => 'Admin Demo',
                'password' => $password,
                'role' => User::ROLE_ADMIN,
                'timezone' => 'Africa/Casablanca',
                'theme' => User::THEME_DARK,
                'palette' => 'ocean',
                'notifications_enabled' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(2),
                'last_activity_at' => now()->subMinutes(30),
            ],
        );

        User::updateOrCreate(
            ['email' => 'student@etuaide.test'],
            [
                'name' => 'Sara Student',
                'password' => $password,
                'role' => User::ROLE_STUDENT,
                'timezone' => 'Africa/Casablanca',
                'theme' => User::THEME_LIGHT,
                'palette' => 'sunrise',
                'notifications_enabled' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subHours(4),
                'last_activity_at' => now()->subMinutes(20),
            ],
        );

        User::updateOrCreate(
            ['email' => 'amal@etuaide.test'],
            [
                'name' => 'Amal Focus',
                'password' => $password,
                'role' => User::ROLE_STUDENT,
                'timezone' => 'Europe/Paris',
                'theme' => User::THEME_LIGHT,
                'palette' => 'forest',
                'notifications_enabled' => true,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(1),
                'last_activity_at' => now()->subHours(6),
            ],
        );

        User::updateOrCreate(
            ['email' => 'inactive@etuaide.test'],
            [
                'name' => 'Youssef Inactive',
                'password' => $password,
                'role' => User::ROLE_STUDENT,
                'timezone' => 'Africa/Casablanca',
                'theme' => User::THEME_DARK,
                'palette' => 'stone',
                'notifications_enabled' => false,
                'email_verified_at' => now(),
                'last_login_at' => now()->subDays(24),
                'last_activity_at' => now()->subDays(19),
            ],
        );
    }
}
