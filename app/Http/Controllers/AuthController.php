<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $request->session()->regenerate();

        return $this->buildAuthResponse(Auth::user()->id);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }

    public function user(Request $request)
    {
        return $this->buildAuthResponse($request->user()->id);
    }

    private function buildAuthResponse(int $userId)
    {
        $user = Auth::getProvider()->retrieveById($userId)->load(['roles.permissions', 'permissions']);

        $permissions = $user->permissions
            ->pluck('name')
            ->merge($user->roles->flatMap(fn ($role) => $role->permissions->pluck('name')))
            ->unique()
            ->values();

        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->values(),
            'permissions' => $permissions,
        ]);
    }
}
