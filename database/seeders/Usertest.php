<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class Usertest extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'afonso@gmail.com'], // Condição para encontrar o usuário
            [
                'name' => 'afonso',
                'last_name' => 'afonso',
                'email' => 'afonso@gmail.com',
                'password' => bcrypt('12345678@'), // Criptografa a senha antes de salvar
                'active' => 1, // Pode ser usado como boolean
                
            ]
        );
    }
}
