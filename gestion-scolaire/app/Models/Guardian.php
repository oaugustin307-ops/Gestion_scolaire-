<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * Guardian
 * 
 * Ce modèle représente un parent/tuteur d'élèves.
 * Un tuteur peut avoir plusieurs enfants (élèves) inscrits dans l'école.
 * 
 * Fonctionnalités principales :
 * - Gestion des informations du tuteur (nom, email, téléphone, mot de passe)
 * - Relation avec les enfants (élèves)
 * - Authentification pour accéder aux informations de ses enfants
 */
class Guardian extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Le nom de la table dans la base de données
     * 
     * @var string
     */
    protected $table = 'parents';

    /**
     * Les attributs qui peuvent être assignés en masse
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',   // Prénom du tuteur
        'last_name',    // Nom de famille du tuteur
        'email',        // Email du tuteur (unique)
        'phone',        // Téléphone du tuteur
        'password',     // Mot de passe haché
    ];

    /**
     * Les attributs qui doivent être cachés lors de la sérialisation
     * 
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les conversions de types pour les attributs
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',  // Hache automatiquement le mot de passe
    ];

    /**
     * Relation : Un tuteur peut avoir plusieurs enfants (élèves)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    /**
     * Accessseur : Retourne le nom complet du tuteur
     * 
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
