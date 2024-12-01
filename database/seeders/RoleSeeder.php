<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role' => 'Patient',
                'marcacao' => true,
                'criar_user' => false,
                'relatorio' => false,
            ],
            [
                'role' => 'Admin',
                'marcacao' => true,
                'criar_user' => true,
                'relatorio' => false,
            ],
            [
                'role' => 'Doctor',
                'marcacao' => false,
                'criar_user' => false,
                'relatorio' => true,
            ],
            [
                'role' => 'Nurse',
                'marcacao' => true,
                'criar_user' => false,
                'relatorio' => true,
            ],
        ]);
    }
}
