<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles.permissions', 'permissions'])->latest()->get();

        return response()->json($users);
    }

    public function stats()
    {
        return response()->json([
            'total_users' => User::count(),
            'admins' => User::whereHas('roles', fn ($query) => $query->where('name', 'SuperAdmin'))->count(),
            'users_without_role' => User::doesntHave('roles')->count(),
            'users_with_direct_permissions' => User::has('permissions')->count(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);

        $fullName = $validated['first_name'].' '.$validated['last_name'];

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'name' => $fullName,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->sync([$validated['role_id']]);
        $user->permissions()->sync($validated['permission_ids'] ?? []);

        return response()->json(['message' => 'User created successfully']);
    }

    public function show($id)
    {
        $user = User::with(['roles', 'permissions'])->findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:6',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);

        $fullName = $validated['first_name'].' '.$validated['last_name'];

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'name' => $fullName,
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->roles()->sync([$validated['role_id']]);
        $user->permissions()->sync($validated['permission_ids'] ?? []);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
