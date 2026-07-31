# API REST - Application Mobile de Suivi Scolaire

## Description

Cette API REST fournit les endpoints nécessaires pour l'application mobile de suivi scolaire parent-enfant. Elle permet aux parents de consulter les informations académiques, financières et administratives de leurs enfants en temps réel.

## Base URL

```
http://localhost:8000/api
```

## Authentification

L'API utilise Laravel Sanctum pour l'authentification par token. Les parents doivent d'abord se connecter pour obtenir un token d'accès.

### Connexion

**Endpoint:** `POST /api/guardian/login`

**Corps de la requête:**
```json
{
  "email": "pierre.ouedraogo@ecole.com",
  "password": "password"
}
```

**Réponse:**
```json
{
  "success": true,
  "message": "Connexion réussie",
  "data": {
    "guardian": {
      "id": 1,
      "first_name": "Pierre",
      "last_name": "OUEDRAOGO",
      "email": "pierre.ouedraogo@ecole.com",
      "phone": "+226 70 00 00 01"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  }
}
```

### Utilisation du Token

Ajoutez le token dans l'en-tête Authorization:
```
Authorization: Bearer {token}
```

### Déconnexion

**Endpoint:** `POST /api/guardian/logout`

**En-têtes:**
```
Authorization: Bearer {token}
```

### Modification du mot de passe

**Endpoint:** `POST /api/guardian/change-password`

**Corps de la requête:**
```json
{
  "current_password": "password",
  "new_password": "nouveaumdp123",
  "new_password_confirmation": "nouveaumdp123"
}
```

## Endpoints

### 1. Profil Parent

#### Obtenir le profil
**Endpoint:** `GET /api/guardian/profile`

**Réponse:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "first_name": "Pierre",
    "last_name": "OUEDRAOGO",
    "email": "pierre.ouedraogo@ecole.com",
    "phone": "+226 70 00 00 01",
    "children_count": 3
  }
}
```

### 2. Enfants (Élèves)

#### Lister tous les enfants
**Endpoint:** `GET /api/guardian/children`

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "first_name": "Paul",
      "last_name": "OUEDRAOGO",
      "full_name": "Paul OUEDRAOGO",
      "photo": "http://localhost:8000/storage/photos/paul.jpg",
      "class": {
        "id": 1,
        "name": "CM2",
        "level": "Primaire"
      },
      "date_of_birth": "2012-05-15",
      "gender": "M",
      "registration_date": "2024-09-01"
    }
  ]
}
```

#### Détails d'un enfant
**Endpoint:** `GET /api/guardian/children/{id}`

#### Tableau de bord d'un enfant
**Endpoint:** `GET /api/guardian/children/{id}/dashboard`

**Réponse:**
```json
{
  "success": true,
  "data": {
    "student": {
      "id": 1,
      "first_name": "Paul",
      "last_name": "OUEDRAOGO",
      "full_name": "Paul OUEDRAOGO",
      "photo": "http://localhost:8000/storage/photos/paul.jpg",
      "class": {
        "id": 1,
        "name": "CM2",
        "level": "Primaire"
      }
    },
    "averages": {
      "trimester1": 15.5,
      "trimester2": 16.2,
      "trimester3": 14.8,
      "annual": 15.5
    },
    "rank": {
      "position": 3,
      "total": 25
    },
    "recent_grades": [
      {
        "subject": "Mathématiques",
        "grade": 16,
        "trimester": 2,
        "date": "15/06/2026"
      }
    ]
  }
}
```

### 3. Notes

#### Lister les matières
**Endpoint:** `GET /api/guardian/children/{id}/subjects`

#### Toutes les notes
**Endpoint:** `GET /api/guardian/children/{id}/grades`

**Réponse:**
```json
{
  "success": true,
  "data": {
    "student": {
      "id": 1,
      "full_name": "Paul OUEDRAOGO",
      "class": "CM2"
    },
    "annual_average": 15.5,
    "trimesters": {
      "trimester1": {
        "average": 15.5,
        "grades": [
          {
            "subject": "Mathématiques",
            "coefficient": 2,
            "grade": 16,
            "remarks": "Bon travail"
          }
        ]
      },
      "trimester2": {
        "average": 16.2,
        "grades": []
      },
      "trimester3": {
        "average": 14.8,
        "grades": []
      }
    }
  }
}
```

#### Notes par matière
**Endpoint:** `GET /api/guardian/children/{id}/grades/{subjectId}`

#### Notes par trimestre
**Endpoint:** `GET /api/guardian/children/{id}/grades/trimester/{trimester}`

### 4. Paiements (Lecture seule)

> **Note importante:** Les parents peuvent uniquement consulter les paiements. Ils ne peuvent pas créer ou modifier de paiements depuis l'application mobile. Seul le personnel de l'école peut enregistrer les paiements via le système web.

#### Historique des paiements
**Endpoint:** `GET /api/guardian/children/{id}/payments`

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "amount": 2000,
      "payment_date": "15/01/2026",
      "payment_method": "Espèces",
      "receipt_number": "REC-20260115-A1B2C3",
      "remarks": "1er versement"
    }
  ]
}
```

#### Résumé des paiements
**Endpoint:** `GET /api/guardian/children/{id}/payments/summary`

**Réponse:**
```json
{
  "success": true,
  "data": {
    "student": {
      "id": 1,
      "full_name": "Paul OUEDRAOGO",
      "class": "CM2"
    },
    "school_fees": 7000,
    "total_paid": 4000,
    "remaining_balance": 3000,
    "payment_percentage": 57.14
  }
}
```

#### Détails d'un paiement
**Endpoint:** `GET /api/guardian/children/{id}/payments/{paymentId}`

#### URL du reçu
**Endpoint:** `GET /api/guardian/children/{id}/payments/{paymentId}/receipt`

### 5. Absences

#### Liste des absences
**Endpoint:** `GET /api/guardian/children/{id}/attendances`

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "date": "10/06/2026",
      "status": "absent",
      "reason": "Maladie",
      "remarks": "Certificat médical fourni"
    }
  ]
}
```

#### Résumé des absences
**Endpoint:** `GET /api/guardian/children/{id}/attendances/summary`

**Réponse:**
```json
{
  "success": true,
  "data": {
    "student": {
      "id": 1,
      "full_name": "Paul OUEDRAOGO"
    },
    "summary": {
      "total_days": 150,
      "present": 145,
      "absent": 3,
      "late": 2,
      "excused": 0
    }
  }
}
```

### 6. Notifications

#### Toutes les notifications
**Endpoint:** `GET /api/guardian/notifications`

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Réunion parents-professeurs",
      "message": "Une réunion parents-professeurs aura lieu le 25 juin 2026 à 9h00.",
      "type": "meeting",
      "date": "2026-06-20",
      "priority": "high"
    }
  ]
}
```

#### Notifications non lues
**Endpoint:** `GET /api/guardian/notifications/unread`

#### Marquer comme lue
**Endpoint:** `POST /api/guardian/notifications/{id}/read`

## Codes de réponse HTTP

- `200 OK` - Requête réussie
- `400 Bad Request` - Données invalides
- `401 Unauthorized` - Token invalide ou manquant
- `404 Not Found` - Ressource non trouvée
- `500 Internal Server Error` - Erreur serveur

## Installation

1. Cloner le dépôt
2. Exécuter `composer install`
3. Configurer le fichier `.env`
4. Exécuter `php artisan migrate`
5. Exécuter `php artisan db:seed`
6. Exécuter `php artisan serve`

## Identifiants de test

**Parents:**
- pierre.ouedraogo@ecole.com / password
- marie.kabore@ecole.com / password
- jean.zongo@ecole.com / password

## Structure de l'API

```
app/Http/Controllers/Api/
├── GuardianApiController.php      # Authentification des parents
├── StudentApiController.php       # Données des élèves
├── GradeApiController.php         # Notes et moyennes
├── PaymentApiController.php      # Suivi des paiements (lecture seule)
├── AttendanceApiController.php   # Suivi des absences
└── NotificationApiController.php # Notifications et annonces
```

## Technologies utilisées

- Laravel 12
- Laravel Sanctum (Authentification API)
- SQLite (Base de données)
- PHP 8.2

## Développement Android

Pour intégrer cette API dans votre application Android :

1. Utilisez Retrofit pour les requêtes HTTP
2. Implémentez l'intercepteur pour ajouter le token d'authentification
3. Utilisez Gson pour la sérialisation JSON
4. Gérez les codes de réponse HTTP appropriés

## Support

Pour toute question ou problème, contactez l'équipe de développement.
