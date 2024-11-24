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
            
            ['email' => 'afonso@gmail.com'],
            ['name'=> 'afonso' ,'email' => 'afonso@gmail.com' , 'password'=>'12345678@' , 'active'=> '1' , 'function'=> 'medico']
        
        );
    }
}
