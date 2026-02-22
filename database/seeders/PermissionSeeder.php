<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'manage_roles', 'label' => 'Manage Roles'],
            ['name' => 'manage_permissions', 'label' => 'Manage Permissions'],
            ['name' => 'manage_users', 'label' => 'Manage Users'],
            ['name' => 'manage_company', 'label' => 'Manage Company'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
    }
}