<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'phone' => '0987654321',
                'password' => Hash::make('password'),
                'bio' => 'System Administrator',
                'image' => 'https://ui-avatars.com/api/?name=Admin+User',
            ]
        );

        // Assign admin role to the user
        $admin->assignRole($adminRole);
    }
}
