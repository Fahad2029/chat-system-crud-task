<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // SuperAdmin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // simple password
            'role' => 'superadmin',
        ]);

        // SuperUsers
        User::create([
            'name' => 'SuperUser One',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'superuser',
        ]);

        User::create([
            'name' => 'SuperUser Two',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'superuser',
        ]);
    }
}
