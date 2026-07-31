<?php

namespace Database\Seeders;

use App\Models\Guardian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuardianSeeder extends Seeder
{
    /**
     * Exécute le seeder pour créer des parents/tuteurs de test
     * 
     * Cette méthode crée des parents avec leurs informations de connexion
     * pour les tests de l'application.
     */
    public function run(): void
    {
        // Supprimer tous les parents existants
        Guardian::truncate();
        
        // Création des parents de test
        Guardian::create([
            'first_name' => 'Pierre',
            'last_name' => 'OUEDRAOGO',
            'email' => 'pierre.ouedraogo@ecole.com',
            'phone' => '+226 70 00 00 01',
            'password' => Hash::make('password'),
        ]);
        
        Guardian::create([
            'first_name' => 'Marie',
            'last_name' => 'KABORE',
            'email' => 'marie.kabore@ecole.com',
            'phone' => '+226 70 00 00 02',
            'password' => Hash::make('password'),
        ]);
        
        Guardian::create([
            'first_name' => 'Jean',
            'last_name' => 'ZONGO',
            'email' => 'jean.zongo@ecole.com',
            'phone' => '+226 70 00 00 03',
            'password' => Hash::make('password'),
        ]);
    }
}
