<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\CheckPermission;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        /*
        |--------------------------------------------------------------------------
        | Sanctum SPA Middleware
        |--------------------------------------------------------------------------
        */

        $middleware->web(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Custom Middleware Aliases
        |--------------------------------------------------------------------------
        */

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        /*
        |--------------------------------------------------------------------------
        | Return JSON Instead of Redirect for API Auth
        |--------------------------------------------------------------------------
        */

        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }
        });

    })->create();