<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::create([
            'name' => 'asep',
            'birth_date' => '19 September 2007',
            'gender' => 'man',
            'class' => '1B',
            'parents' => 'asep',
            'phone_number' => '08121338342',
            'email' => 'giblartamvan@gmail.com',
            'addres' => 'Bogor',
            'role' => 'Guru',
            'username' => 'giblar',
            'password' => Hash::make('123')
        ]);
        
    }
    
}
