<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Admin
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => 1, // Admin
            'status' => 1,
        ]);

        // Doctor
        DB::table('users')->insert([
            'name' => 'Dr. João Silva',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2, // Doctor
            'status' => 1,
        ]);
    }
} 