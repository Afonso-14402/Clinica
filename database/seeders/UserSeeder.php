<?php

namespace Database\Seeders;
use App\Models\Role; 
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar um usuário e associar a um role
        $role = Role::where('role', 'admin')->first();

        User::create([
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password'),
            'active' => 1,
            'role_id' => $role->id, // Associa o ID da role
            'birth_date' => '1990-01-01',
        ]);
    }
}
