<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtiesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('specialties')->insert([
            ['name' => 'Medicina Geral e Familiar'],
            ['name' => 'Cardiologia'],
            ['name' => 'Pediatria'],
            ['name' => 'Ortopedia'],
            ['name' => 'Dermatologia'],
        ]);
    }
} 