<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->latest()->get();

        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|unique:roles,name',
            'label' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'name'  => $request->name,
            'label' => $request->label,
        ]);

        return response()->json([
            'message' => 'Role created successfully',
            'role'    => $role
        ]);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|unique:roles,name,' . $id,
            'label' => 'required|string|max:255',
        ]);

        $role->update([
            'name'  => $request->name,
            'label' => $request->label,
        ]);

        return response()->json(['message' => 'Role updated successfully']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}