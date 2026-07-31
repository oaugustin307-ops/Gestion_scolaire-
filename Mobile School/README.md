# School Connect — Application Android

Application mobile Android (Kotlin) permettant aux parents de suivre la
scolarité de leurs enfants : notes, paiements, absences et annonces.
Consomme l'API REST Laravel `routes/api.php` (groupe `guardian`).

## Sujet
Projet de développement mobile — UFR/SEA, Université Joseph Ki-Zerbo.
Enseignant : Lionel Marcus G. KABORET.

## Membres du binôme
- Tinwendé — *(à compléter avec le second membre)*

## Fonctionnalités

- **Authentification parent** : connexion, déconnexion, modification du mot de passe
- **Tableau de bord élève** : photo, classe, moyenne annuelle, rang, dernières notes
- **Notes** : consultation par trimestre avec moyenne pondérée par coefficient
- **Paiements** : historique des versements, total payé, reste à payer, % payé
- **Absences** : liste des absences/retards avec motif, résumé chiffré
- **Notifications** : annonces de l'école (réunions, examens, échéances)

## Architecture technique

- **Langage** : Kotlin
- **Réseau** : Retrofit2 + OkHttp + Gson
- **Authentification** : session Laravel par **cookie** (le guard `guardian` utilise
  `'driver' => 'session'`, pas de token Sanctum). Un `PersistentCookieJar` personnalisé
  conserve le cookie de session entre les requêtes et entre les redémarrages de l'app.
- **UI** : View Binding, Material Components 3, RecyclerView
- **Images** : Glide (photo des élèves)

## Installation

1. Ouvrir le dossier `SchoolConnectApp/` dans Android Studio (il régénérera
   automatiquement le wrapper Gradle si besoin — `File > Sync Project with Gradle Files`).
2. Démarrer l'API Laravel sur ta machine :
   ```
   php artisan serve
   ```
3. Vérifier l'URL de l'API dans `RetrofitClient.kt` :
   - **Émulateur Android Studio** : `http://10.0.2.2:8000/api/` (déjà configuré, ne rien changer)
   - **Téléphone physique** (même Wi-Fi que le PC) : remplacer par l'IP locale du PC,
     ex. `http://192.168.1.42:8000/api/`
4. Lancer l'application (Run ▶).

## Comptes de test

Utiliser un email/mot de passe existant dans la table `parents` de la base Laravel.

## Limitations connues

- Le module Notifications utilise actuellement des données simulées côté Laravel
  (`NotificationApiController`), comme prévu dans le contrôleur fourni.
- Le téléchargement du reçu de paiement (`/payments/{id}/receipt`) renvoie une URL ;
  l'ouverture dans un navigateur externe peut être ajoutée si nécessaire.
