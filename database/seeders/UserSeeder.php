<?php

namespace Database\Seeders;

use App\Models\User;
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
            'name'=> 'admin',
            'username' => 'administrator',
            'email' => 'adminschool@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);


        User::create([
            'name' => 'tes_teacher',
            'username' => 'teacher',
            'email' => 'teacher@gmail.com',
            'password' => Hash::make('teacher123'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'tes_siswa',
            'username' => 'siswa',
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
        ]);
    }
}