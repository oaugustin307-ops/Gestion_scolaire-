# Système de Gestion Scolaire

Application web de gestion scolaire pour l'école primaire (CP1 à CM2) permettant un suivi rigoureux financier et pédagogique.

## Sujet du Projet

Développer une application web pour gérer une école primaire avec les fonctionnalités suivantes :

### Fonctionnalités Administratives et Financières
- Inscription des élèves avec informations de base et photo
- Configuration des classes et définition des frais de scolarité par classe
- Enregistrement des paiements effectués par les parents
- Génération de reçus de paiement (format PDF) et calcul du solde restant

### Fonctionnalités Pédagogiques (Notes et Moyennes)
- Saisie des notes par matière pour chaque élève
- Calcul automatique des moyennes trimestrielles
- Tableau de bord affichant le classement des élèves par classe

### Tableau de Bord Global
- Statistiques sur les frais collectés vs frais attendus
- Liste des élèves avec retard de paiement

## Technologies Utilisées

- **PHP** 8.2+
- **Laravel** 12.0
- **MySQL** (base de données)
- **HTML/CSS** (Tailwind CSS via CDN)
- **JavaScript** (pour les interactions dynamiques)
- **Blade** (moteur de templates Laravel)

## Membres du Projet

- [Nom du membre 1]
- [Nom du membre 2]

## Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- MySQL ou SQLite
- Node.js et NPM (optionnel, pour le build des assets)

### Étapes d'installation

1. **Cloner le dépôt**
```bash
git clone [url-du-dépôt]
cd gestion-scolaire
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données**
Éditez le fichier `.env` et configurez les paramètres de votre base de données :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_scolaire
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

5. **Exécuter les migrations**
```bash
php artisan migrate
```

6. **Exécuter les seeders (données initiales)**
```bash
php artisan db:seed
```

Cela créera :
- 6 classes (CP1, CP2, CE1, CE2, CM1, CM2) avec leurs frais de scolarité
- 7 matières (Mathématiques, Français, Sciences, Histoire-Géographie, Anglais, EPS, Arts plastiques)

7. **Démarrer le serveur de développement**
```bash
php artisan serve
```

L'application sera accessible à l'adresse : `http://localhost:8000`

## Structure de la Base de Données

### Tables principales
- **users** : Utilisateurs du système (avec rôle manager/teacher)
- **classes** : Classes de l'école (CP1 à CM2)
- **students** : Élèves inscrits
- **subjects** : Matières enseignées
- **grades** : Notes des élèves par matière et trimestre
- **payments** : Paiements effectués par les parents

### Relations
- Une classe a plusieurs élèves
- Un élève appartient à une classe
- Un élève a plusieurs notes
- Une note appartient à un élève, une matière et une classe
- Un élève a plusieurs paiements

## Fonctionnalités de l'Application

### Gestion des Classes
- Créer, modifier, supprimer des classes
- Définir les frais de scolarité par classe
- Voir les élèves de chaque classe

### Gestion des Élèves
- Inscrire de nouveaux élèves avec photo
- Modifier les informations des élèves
- Voir le profil complet d'un élève (notes, paiements)
- Supprimer des élèves

### Gestion des Matières
- Créer, modifier, supprimer des matières
- Définir les coefficients des matières

### Gestion des Notes
- Saisir les notes par élève, matière et trimestre
- Modifier les notes existantes
- Voir toutes les notes enregistrées

### Gestion des Paiements
- Enregistrer les paiements des parents
- Générer des reçus de paiement (PDF)
- Voir l'historique des paiements
- Suivre les soldes restants

### Tableau de Bord
- Vue d'ensemble des statistiques (nombre d'élèves, classes, frais collectés)
- Liste des élèves avec retard de paiement
- Paiements récents

## Routes Principales

- `/dashboard` - Tableau de bord
- `/classes` - Gestion des classes
- `/students` - Gestion des élèves
- `/subjects` - Gestion des matières
- `/grades` - Gestion des notes
- `/payments` - Gestion des paiements

## Dépôt GitHub

Le projet doit être déposé sur GitHub et le lien envoyé à : marcus.kaboret@ukz.bf

**Date limite :** 31 mai 2026 à 23:59

## License

Ce projet est réalisé dans le cadre d'un projet académique.
