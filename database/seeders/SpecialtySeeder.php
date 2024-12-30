<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    public function run()
    {
        Specialty::insert([
            ['name' => 'Medicina Geral e Familiar', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Anestesiologia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cardiologia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cirurgia Geral', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dermatologia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Endocrinologia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ginecologia e Obstetrícia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hematologia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Medicina Interna', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Neurologia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oftalmologia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ortopedia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pediatria', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Psiquiatria', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Radiologia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Urologia', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

