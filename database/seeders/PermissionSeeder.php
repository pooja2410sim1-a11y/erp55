<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // User management actions
            ['name' => 'manage_users', 'label' => 'Manage Users'],
            ['name' => 'user.view', 'label' => 'View Users'],
            ['name' => 'user.add', 'label' => 'Add Users'],
            ['name' => 'user.edit', 'label' => 'Edit Users'],
            ['name' => 'user.approve', 'label' => 'Approve Users'],
            ['name' => 'user.delete', 'label' => 'Delete Users'],

            // Role & permission management
            ['name' => 'manage_roles', 'label' => 'Manage Roles'],
            ['name' => 'manage_permissions', 'label' => 'Manage Permissions'],
            ['name' => 'role.view', 'label' => 'View Roles'],
            ['name' => 'role.add', 'label' => 'Add Roles'],
            ['name' => 'role.edit', 'label' => 'Edit Roles'],
            ['name' => 'role.delete', 'label' => 'Delete Roles'],

            // Dashboard access
            ['name' => 'dashboard.view', 'label' => 'View Dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
