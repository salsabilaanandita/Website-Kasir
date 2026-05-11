<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),    
            ]);
        }

        if (!User::where('email', 'staf@gmail.com')->exists()) {
            User::create([
            'name' => 'Staf Biasa', 
            'email' => 'staf@gmail.com', 
            'password' => Hash::make('123'),
            'role' => 'staff',   
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            ]);
        }
    }
}

