<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_back_in_after_logging_out(): void
    {
        $credentials = [
            'name' => 'Malak',
            'email' => 'malak@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $this->post(route('register'), $credentials)
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();

        $this->post(route('login'), [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }
}
