<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
        ]);

        $roles = [
            ['name' => 'SuperAdmin', 'label' => 'Super Administrator'],
            ['name' => 'Admin', 'label' => 'Administrator'],
            ['name' => 'Merchandiser', 'label' => 'Merchandiser'],
            ['name' => 'PurchaseManager', 'label' => 'Purchase Manager'],
            ['name' => 'AccountsManager', 'label' => 'Accounts Manager'],
        ];

        $createdRoles = [];
        foreach ($roles as $roleData) {
            $createdRoles[$roleData['name']] = Role::firstOrCreate(
                ['name' => $roleData['name']],
                ['label' => $roleData['label']]
            );
        }

        $allPermissionIds = Permission::query()->pluck('id')->all();

        $createdRoles['SuperAdmin']->permissions()->sync($allPermissionIds);

        $createdRoles['Admin']->permissions()->sync(
            Permission::query()->whereIn('name', [
                'dashboard.view',
                'manage_users',
                'manage_roles',
                'manage_permissions',
                'user.view',
                'user.add',
                'user.edit',
                'user.approve',
                'user.delete',
                'role.view',
                'role.add',
                'role.edit',
                'role.delete',
            ])->pluck('id')->all()
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@kaushal.local'],
            [
                'name' => 'Super Admin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );

        $admin->roles()->sync([$createdRoles['SuperAdmin']->id]);
    }
}
