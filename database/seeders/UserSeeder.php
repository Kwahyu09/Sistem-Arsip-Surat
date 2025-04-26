<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'username' => 'Admin123',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Staff
        User::create([
            'name' => 'Staff User',
            'username' => 'Staff123',
            'email' => 'staff@example.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);


        // Mahasiswa
        User::create([
            'name' => 'Staff Bidang User',
            'username' => 'Staffbidang123',
            'email' => 'staffbidang@example.com',
            'password' => Hash::make('password123'),
            'role' => 'staff_bidang',
        ]);
    }
}
