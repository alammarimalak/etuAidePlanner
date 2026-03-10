<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = $request->user();
        if ($user) {
            $now = now();
            $lastActivity = $user->last_activity_at;
            $shouldUpdate = !$lastActivity || $lastActivity->lt($now->copy()->subMinutes(5));

            if ($shouldUpdate) {
                $user->forceFill([
                    'last_activity_at' => $now,
                ])->save();
            }
        }

        return $response;
    }
}
