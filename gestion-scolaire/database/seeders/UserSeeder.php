<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();
        
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@ecole.com',
            'password' => Hash::make('password'),
            'role' => 'gestionnaire',
        ]);
        
        User::create([
            'name' => 'Enseignant Test',
            'email' => 'enseignant@ecole.com',
            'password' => Hash::make('password'),
            'role' => 'enseignant',
        ]);
    }
}
