<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    // Statuts possibles d'un paiement
    public const STATUS_EN_ATTENTE = 'en_attente';
    public const STATUS_VALIDEE = 'validee';
    public const STATUS_REJETEE = 'rejetee';

    protected $fillable = [
        'student_id',
        'amount',
        'payment_date',
        'payment_method',
        'receipt_number',
        'remarks',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function isValidated(): bool
    {
        return $this->status === self::STATUS_VALIDEE;
    }
}