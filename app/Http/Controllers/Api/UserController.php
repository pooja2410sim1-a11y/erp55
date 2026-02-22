<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->get();

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'role_id'    => 'required|exists:roles,id',
        ]);

        $fullName = $request->first_name . ' ' . $request->last_name;

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'name'       => $fullName,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);

        $user->roles()->sync([$request->role_id]);

        return response()->json(['message' => 'User created successfully']);
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $id,
            'role_id'    => 'required|exists:roles,id',
        ]);

        $fullName = $request->first_name . ' ' . $request->last_name;

        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'name'       => $fullName,
            'email'      => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $user->roles()->sync([$request->role_id]);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}