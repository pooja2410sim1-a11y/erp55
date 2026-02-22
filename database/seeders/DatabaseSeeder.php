<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Create SuperAdmin Role
        |--------------------------------------------------------------------------
        */
        $superAdminRole = Role::firstOrCreate([
            'name' => 'SuperAdmin'
        ], [
            'label' => 'Super Administrator'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Admin User
        |--------------------------------------------------------------------------
        */
        $admin = User::firstOrCreate([
            'email' => 'admin@kaushal.local'
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password123')
        ]);

        /*
        |--------------------------------------------------------------------------
        | Attach Role to Admin
        |--------------------------------------------------------------------------
        */
        $admin->roles()->sync([$superAdminRole->id]);

        /*
        |--------------------------------------------------------------------------
        | Call Other Seeders
        |--------------------------------------------------------------------------
        */
        $this->call([
            PermissionSeeder::class,
        ]);
    }
}