<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Exécute le seeder pour créer des notifications de test
     * 
     * Cette méthode crée des notifications pour les tests de l'application.
     */
    public function run(): void
    {
        // Supprimer toutes les notifications existantes
        Notification::truncate();
        
        // Création des notifications de test
        Notification::create([
            'title' => 'Réunion parents-professeurs',
            'message' => 'Une réunion parents-professeurs aura lieu le 25 juin 2026 à 9h00 dans la salle de conférence de l\'école. La présence de tous les parents est fortement recommandée.',
            'type' => 'meeting',
            'priority' => 'high',
            'date' => '2026-06-25',
            'is_read' => false,
        ]);
        
        Notification::create([
            'title' => 'Examens du 3ème trimestre',
            'message' => 'Les examens du 3ème trimestre commenceront le 1er juillet 2026 et se termineront le 15 juillet 2026. Veuillez préparer vos enfants et vous assurer qu\'ils révisent leurs cours.',
            'type' => 'exam',
            'priority' => 'high',
            'date' => '2026-07-01',
            'is_read' => false,
        ]);
        
        Notification::create([
            'title' => 'Échéance de paiement',
            'message' => 'Le dernier versement des frais de scolarité est dû avant le 30 juin 2026. Merci de bien vouloir régler les montants restants pour éviter tout désagrément.',
            'type' => 'payment',
            'priority' => 'medium',
            'date' => '2026-06-30',
            'is_read' => false,
        ]);
        
        Notification::create([
            'title' => 'Vacances scolaires',
            'message' => 'Les vacances d\'été commenceront le 20 juillet 2026. La reprise des cours est prévue pour le 1er septembre 2026. Bonnes vacances à tous !',
            'type' => 'general',
            'priority' => 'low',
            'date' => '2026-07-20',
            'is_read' => false,
        ]);
        
        Notification::create([
            'title' => 'Journée portes ouvertes',
            'message' => 'L\'école organise une journée portes ouvertes le 15 juin 2026 de 8h00 à 16h00. Venez découvrir nos installations et rencontrer l\'équipe pédagogique.',
            'type' => 'general',
            'priority' => 'medium',
            'date' => '2026-06-15',
            'is_read' => true,
        ]);
        
        $this->command->info('Notifications de test créées avec succès.');
    }
}
