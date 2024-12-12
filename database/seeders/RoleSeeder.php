<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::insert([
            ['role' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['role' => 'Doctor', 'created_at' => now(), 'updated_at' => now()],
            ['role' => 'Patient', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

