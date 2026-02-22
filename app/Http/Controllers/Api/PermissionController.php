<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json([
            'permissions' => Permission::all(),
            'roles'       => Role::with('permissions')->get(),
        ]);
    }

    public function updateRolePermissions(Request $request, $roleId)
    {
        $request->validate([
            'permissions' => 'array'
        ]);

        $role = Role::findOrFail($roleId);

        $role->permissions()->sync($request->permissions ?? []);

        return response()->json(['message' => 'Permissions updated successfully']);
    }
}