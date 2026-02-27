<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect('/login');
        }

        if (!auth()->user()->hasRole($role)) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden.',
                ], 403);
            }

            return redirect('/');
        }

        return $next($request);
    }
}
