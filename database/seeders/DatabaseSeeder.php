<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create test user
        User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'bio' => 'Test user bio',
            'image' => 'https://ui-avatars.com/api/?name=Test+User',
        ]);

        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
