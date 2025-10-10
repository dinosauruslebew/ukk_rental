<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // akun admin
        User::create([
            'name' => 'Admin Rental',
            'email' => 'admin@rental.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // akun user biasa
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@rental.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
