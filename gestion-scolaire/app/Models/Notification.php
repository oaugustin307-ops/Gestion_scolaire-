<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Notification
 * 
 * Ce modèle représente une notification/annonce de l'école.
 * Il permet de gérer les communications avec les parents (réunions, examens, paiements, etc.).
 */
class Notification extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'message',
        'type',
        'priority',
        'date',
        'is_read',
    ];

    /**
     * Les conversions de types pour les attributs
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'is_read' => 'boolean',
    ];
}
