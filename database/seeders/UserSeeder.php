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
        $users = [
            [
                'name' => 'Superadmin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'no_hp' => '08111111111',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'no_hp' => '08222222222',
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('password'),
                'no_hp' => '081234567890',
                'role' => 'user',
            ],
            [
                'name' => 'Budi Pembeli',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password'),
                'no_hp' => '08999999999',
                'role' => 'user',
            ]
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']], 
                $user
            );
        }
    }
}
