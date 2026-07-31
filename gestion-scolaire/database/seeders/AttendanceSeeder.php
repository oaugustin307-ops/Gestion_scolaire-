<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        Attendance::truncate();
        
        $students = Student::all();
        
        if ($students->isEmpty()) {
            $this->command->warn('Veuillez exécuter StudentSeeder d\'abord.');
            return;
        }
        
        // Créer des présences pour les 30 derniers jours
        foreach ($students as $student) {
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i);
                
                // Sauter les week-ends
                if ($date->isWeekend()) {
                    continue;
                }
                
                // Générer un statut aléatoire avec plus de présences
                $status = $this->getRandomStatus();
                
                Attendance::create([
                    'student_id' => $student->id,
                    'date' => $date->format('Y-m-d'),
                    'status' => $status,
                    'reason' => $status === 'absent' ? 'Maladie' : ($status === 'excused' ? 'Raison familiale' : null),
                    'remarks' => $status === 'late' ? 'Retard de 10 minutes' : null,
                ]);
            }
        }
        
        $this->command->info('Présences de test créées avec succès.');
    }
    
    private function getRandomStatus()
    {
        $statuses = ['present', 'present', 'present', 'present', 'present', 'present', 'present', 'late', 'absent', 'excused'];
        return $statuses[array_rand($statuses)];
    }
}
