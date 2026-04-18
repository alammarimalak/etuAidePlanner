<?php

use App\Http\Middleware\EnsureAuthenticated;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\UpdateLastActivity;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => EnsureAuthenticated::class,
            'role' => EnsureRole::class,
            'activity' => UpdateLastActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $exception, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            $statusCode = $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;

            $view = match (true) {
                view()->exists('errors.' . $statusCode) => 'errors.' . $statusCode,
                $statusCode >= 400 && $statusCode < 500 => 'errors.4xx',
                default => 'errors.5xx',
            };

            return response()->view($view, [
                'exception' => $exception,
                'statusCode' => $statusCode,
            ], $statusCode);
        });
    })->create();
