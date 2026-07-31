<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Attendance
 * 
 * Ce modèle représente la présence/absence d'un élève.
 * Il permet de suivre les absences, retards et présences des élèves.
 */
class Attendance extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'date',
        'status',
        'reason',
        'remarks',
    ];

    /**
     * Les conversions de types pour les attributs
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relation : Une appartient à un élève
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
