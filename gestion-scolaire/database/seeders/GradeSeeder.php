<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Exécute le seeder pour créer des notes de test
     * 
     * Cette méthode crée des notes pour les élèves existants
     * afin de tester le calcul des moyennes.
     */
    public function run(): void
    {
        // Supprimer toutes les notes existantes
        Grade::truncate();
        
        // Récupérer les élèves et matières existants
        $students = Student::with('class')->get();
        $subjects = Subject::all();
        
        if ($students->isEmpty() || $subjects->isEmpty()) {
            $this->command->warn('Veuillez exécuter StudentSeeder et SubjectSeeder d\'abord.');
            return;
        }
        
        // Création des notes pour chaque élève
        foreach ($students as $student) {
            // Récupérer les matières de la classe de l'élève
            $classSubjects = Subject::where('class_id', $student->class_id)->get();
            
            foreach ($classSubjects as $subject) {
                // Notes pour le trimestre 1
                Grade::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'class_id' => $student->class_id,
                    'grade' => rand(12, 18),
                    'trimester' => 1,
                    'remarks' => 'Bon travail',
                ]);
                
                // Notes pour le trimestre 2
                Grade::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'class_id' => $student->class_id,
                    'grade' => rand(13, 19),
                    'trimester' => 2,
                    'remarks' => 'Progression constante',
                ]);
                
                // Notes pour le trimestre 3
                Grade::create([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'class_id' => $student->class_id,
                    'grade' => rand(11, 17),
                    'trimester' => 3,
                    'remarks' => 'Effort à fournir',
                ]);
            }
        }
        
        $this->command->info('Notes de test créées avec succès.');
    }
}
