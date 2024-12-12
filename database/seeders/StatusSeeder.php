<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    public function run()
    {
        Status::insert([
            ['status' => 'Scheduled', 'created_at' => now(), 'updated_at' => now()],
            ['status' => 'Completed', 'created_at' => now(), 'updated_at' => now()],
            ['status' => 'Cancelled', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

