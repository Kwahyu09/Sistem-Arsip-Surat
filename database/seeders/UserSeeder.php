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


        // Staff Bidang
        User::create([
            'name' => 'Staff Bidang It',
            'username' => 'Staff_it',
            'email' => 'staffIt@example.com',
            'password' => Hash::make('password123'),
            'role' => 'staff_bidang',
        ]);
        User::create([
            'name' => 'Staff Bidang Keuangan',
            'username' => 'Staff_Keuangan',
            'email' => 'staff_keuangan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'staff_bidang',
        ]);
        User::create([
            'name' => 'Staff Bidang Acara',
            'username' => 'Staff_acara',
            'email' => 'staff_acara@example.com',
            'password' => Hash::make('password123'),
            'role' => 'staff_bidang',
        ]);
    }
}
