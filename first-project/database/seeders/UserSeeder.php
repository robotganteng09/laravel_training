<?php

namespace Database\Seeders;

use App\Models\CartModel;
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
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // cek berdasarkan email
            [
                'name' => 'Admin Utama',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer Satu',
                'role' => 'customer',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
