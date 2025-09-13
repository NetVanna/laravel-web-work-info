<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // 🔒 hashed password
            'phone' => '0123456789',
            'address' => 'Phnom Penh, Cambodia',
            'role' => 'admin',
            'status' => '1',
        ]);

        // Instructor User
        User::create([
            'name' => 'Instructor User',
            'email' => 'instructor@example.com',
            'password' => Hash::make('password123'),
            'phone' => '0987654321',
            'address' => 'Siem Reap, Cambodia',
            'role' => 'instructor',
            'status' => '1',
        ]);

        // Normal User
        User::create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'phone' => '011223344',
            'address' => 'Battambang, Cambodia',
            'role' => 'user',
            'status' => '1',
        ]);
    }
}
