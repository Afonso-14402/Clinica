<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    
    public function run()
    {
        $specialties = ['Cardiology', 'Dermatology', 'Neurology', 'Orthopedics'];
    
        foreach ($specialties as $specialty) {
            // Verifica se a especialidade já existe antes de inserir
            Specialty::firstOrCreate([
                'name' => $specialty,
            ]);
        }
    }
}
