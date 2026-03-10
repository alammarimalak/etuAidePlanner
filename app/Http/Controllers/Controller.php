<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected function currentUser(): User
    {
        $user = auth()->user();
        if ($user instanceof User) {
            return $user;
        }

        return User::query()->firstOrFail();
    }
}
