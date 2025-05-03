<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'password' => Hash::make('password'),
            'bio' => 'System Administrator',
        ]);

        $admin->assignRole('admin');
    }
}
