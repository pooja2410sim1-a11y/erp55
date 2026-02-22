<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // If user is not logged in, let auth:sanctum handle it
        if (!auth()->check()) {
            return $next($request);
        }

        // If user does not have required role
        if (!auth()->user()->hasRole($role)) {

            // If API request → return JSON
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden.'
                ], 403);
            }

            // If web request → redirect to dashboard
            return redirect('/');
        }

        return $next($request);
    }
}