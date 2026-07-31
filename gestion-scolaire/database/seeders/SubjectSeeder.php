<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Supprimer toutes les matières existantes
        Subject::truncate();
        
        $classes = ClassRoom::all();
        
        // 6 matières pour chaque classe (indépendamment du nom)
        $subjects = [
            ['name' => 'Mathématiques', 'code' => 'MAT', 'coefficient' => 4],
            ['name' => 'Français', 'code' => 'FRA', 'coefficient' => 4],
            ['name' => 'Sciences', 'code' => 'SCI', 'coefficient' => 2],
            ['name' => 'Histoire-Géographie', 'code' => 'HIS', 'coefficient' => 2],
            ['name' => 'Anglais', 'code' => 'ANG', 'coefficient' => 2],
            ['name' => 'Éducation physique', 'code' => 'EPS', 'coefficient' => 1],
        ];

        foreach ($classes as $class) {
            foreach ($subjects as $subject) {
                Subject::create([
                    'class_id' => $class->id,
                    'name' => $subject['name'],
                    'code' => $subject['code'] . '_' . $class->name, // Code unique par classe
                    'coefficient' => $subject['coefficient'],
                ]);
            }
        }
    }
}
