<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
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
                'name' => 'Admin Utama',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Manager Operasional',
                'email' => 'manager@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'manager',
            ],
            [
                'name' => 'Staf Kasir 1',
                'email' => 'staf@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'staff',
            ],
            [
                'name' => 'Rian Pratama (Kasir Pagi)',
                'email' => 'rian.kasir@kasirapp.com',
                'password' => Hash::make('123'),
                'role' => 'staff',
            ],
            [
                'name' => 'Maya Anggraini (Kasir Sore)',
                'email' => 'maya.kasir@kasirapp.com',
                'password' => Hash::make('123'),
                'role' => 'staff',
            ],
            [
                'name' => 'Doni Setiawan (Kasir Malam)',
                'email' => 'doni.kasir@kasirapp.com',
                'password' => Hash::make('123'),
                'role' => 'staff',
            ],
            [
                'name' => 'Hendra Kusuma (Supervisor)',
                'email' => 'hendra.spv@kasirapp.com',
                'password' => Hash::make('123'),
                'role' => 'manager',
            ],
            [
                'name' => 'Bambang Suryo (Gudang)',
                'email' => 'bambang.gudang@kasirapp.com',
                'password' => Hash::make('123'),
                'role' => 'staff',
            ],
            [
                'name' => 'Sarah Melati (Keuangan)',
                'email' => 'sarah.finance@kasirapp.com',
                'password' => Hash::make('123'),
                'role' => 'manager',
            ],
            [
                'name' => 'Nita Kusuma (Kasir)',
                'email' => 'nita.kasir@kasirapp.com',
                'password' => Hash::make('123'),
                'role' => 'staff',
            ],
            [
                'name' => 'Reza Ramadhan (Kasir)',
                'email' => 'reza.kasir@kasirapp.com',
                'password' => Hash::make('123'),
                'role' => 'staff',
            ],
            [
                'name' => 'Ahmad Fauzan (IT Admin)',
                'email' => 'ahmad.admin@kasirapp.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => $u['password'],
                    'role' => $u['role'],
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
        }
    }
}
