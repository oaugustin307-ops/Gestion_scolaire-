<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    public function run(): void
    {
        // Supprimer toutes les classes existantes
        ClassRoom::truncate();
        
        $classes = [
            ['name' => 'CP1', 'level' => 'Primaire', 'school_fees' => 50000],
            ['name' => 'CP2', 'level' => 'Primaire', 'school_fees' => 50000],
            ['name' => 'CE1', 'level' => 'Primaire', 'school_fees' => 60000],
            ['name' => 'CE2', 'level' => 'Primaire', 'school_fees' => 60000],
            ['name' => 'CM1', 'level' => 'Primaire', 'school_fees' => 70000],
            ['name' => 'CM2', 'level' => 'Primaire', 'school_fees' => 70000],
        ];

        foreach ($classes as $class) {
            ClassRoom::create($class);
        }
    }
}
