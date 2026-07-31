<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Guardian;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Exécute le seeder pour créer des élèves de test
     * 
     * Cette méthode crée des élèves avec leurs informations
     * et les associe à des classes et des parents/tuteurs.
     */
    public function run(): void
    {
        // Supprimer tous les élèves existants
        Student::truncate();
        
        // Récupérer les classes et parents existants
        $classes = ClassRoom::all();
        $guardians = Guardian::all();
        
        if ($classes->isEmpty() || $guardians->isEmpty()) {
            $this->command->warn('Veuillez exécuter ClassRoomSeeder et GuardianSeeder d\'abord.');
            return;
        }
        
        // Création des élèves de test associés aux parents
        // Parent 1 : Pierre OUEDRAOGO
        Student::create([
            'class_id' => $classes->where('name', 'CM2')->first()->id,
            'parent_id' => $guardians->where('email', 'pierre.ouedraogo@ecole.com')->first()->id,
            'first_name' => 'Paul',
            'last_name' => 'OUEDRAOGO',
            'date_of_birth' => '2012-05-15',
            'gender' => 'M',
            'parent_name' => 'Pierre OUEDRAOGO',
            'parent_phone' => '+226 70 00 00 01',
            'address' => 'Ouagadougou, Quartier Patte d\'Oie',
            'registration_date' => '2024-09-01',
        ]);
        
        Student::create([
            'class_id' => $classes->where('name', 'CE2')->first()->id,
            'parent_id' => $guardians->where('email', 'pierre.ouedraogo@ecole.com')->first()->id,
            'first_name' => 'Marie',
            'last_name' => 'OUEDRAOGO',
            'date_of_birth' => '2014-08-20',
            'gender' => 'F',
            'parent_name' => 'Pierre OUEDRAOGO',
            'parent_phone' => '+226 70 00 00 01',
            'address' => 'Ouagadougou, Quartier Patte d\'Oie',
            'registration_date' => '2024-09-01',
        ]);
        
        Student::create([
            'class_id' => $classes->where('name', 'CP2')->first()->id,
            'parent_id' => $guardians->where('email', 'pierre.ouedraogo@ecole.com')->first()->id,
            'first_name' => 'Jean',
            'last_name' => 'OUEDRAOGO',
            'date_of_birth' => '2016-03-10',
            'gender' => 'M',
            'parent_name' => 'Pierre OUEDRAOGO',
            'parent_phone' => '+226 70 00 00 01',
            'address' => 'Ouagadougou, Quartier Patte d\'Oie',
            'registration_date' => '2024-09-01',
        ]);
        
        // Parent 2 : Marie KABORE
        Student::create([
            'class_id' => $classes->where('name', 'CM1')->first()->id,
            'parent_id' => $guardians->where('email', 'marie.kabore@ecole.com')->first()->id,
            'first_name' => 'Aminata',
            'last_name' => 'KABORE',
            'date_of_birth' => '2013-07-25',
            'gender' => 'F',
            'parent_name' => 'Marie KABORE',
            'parent_phone' => '+226 70 00 00 02',
            'address' => 'Ouagadougou, Quartier Koulouba',
            'registration_date' => '2024-09-01',
        ]);
        
        // Parent 3 : Jean ZONGO
        Student::create([
            'class_id' => $classes->where('name', 'CE1')->first()->id,
            'parent_id' => $guardians->where('email', 'jean.zongo@ecole.com')->first()->id,
            'first_name' => 'Kouame',
            'last_name' => 'ZONGO',
            'date_of_birth' => '2015-11-05',
            'gender' => 'M',
            'parent_name' => 'Jean ZONGO',
            'parent_phone' => '+226 70 00 00 03',
            'address' => 'Ouagadougou, Quartier Zone 1',
            'registration_date' => '2024-09-01',
        ]);
        
        Student::create([
            'class_id' => $classes->where('name', 'CP1')->first()->id,
            'parent_id' => $guardians->where('email', 'jean.zongo@ecole.com')->first()->id,
            'first_name' => 'Fatou',
            'last_name' => 'ZONGO',
            'date_of_birth' => '2017-02-14',
            'gender' => 'F',
            'parent_name' => 'Jean ZONGO',
            'parent_phone' => '+226 70 00 00 03',
            'address' => 'Ouagadougou, Quartier Zone 1',
            'registration_date' => '2024-09-01',
        ]);
    }
}
