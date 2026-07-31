<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'parent_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'parent_name',
        'parent_phone',
        'address',
        'photo',
        'registration_date',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'registration_date' => 'date',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class, 'parent_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getTotalPaymentsAttribute(): float
    {
        // Seuls les paiements VALIDÉS comptent dans le total payé.
        // Un paiement "en attente" déclaré par un parent ne doit pas
        // réduire artificiellement le solde restant avant validation par l'école.
        return $this->payments()
            ->where('status', \App\Models\Payment::STATUS_VALIDEE)
            ->sum('amount');
    }

    public function getRemainingBalanceAttribute(): float
    {
        $totalFees = $this->class->school_fees;
        return $totalFees - $this->total_payments;
    }

    public function getTrimesterAverageAttribute(int $trimester): float
    {
        $grades = $this->grades()->where('trimester', $trimester)->get();
        
        if ($grades->isEmpty()) {
            return 0;
        }

        $total = 0;
        $totalCoefficient = 0;

        foreach ($grades as $grade) {
            $coefficient = $grade->subject->coefficient;
            $total += $grade->grade * $coefficient;
            $totalCoefficient += $coefficient;
        }

        return $totalCoefficient > 0 ? round($total / $totalCoefficient, 2) : 0;
    }

    public function getTrimester1AverageAttribute(): float
    {
        return $this->getTrimesterAverageAttribute(1);
    }

    public function getTrimester2AverageAttribute(): float
    {
        return $this->getTrimesterAverageAttribute(2);
    }

    public function getTrimester3AverageAttribute(): float
    {
        return $this->getTrimesterAverageAttribute(3);
    }

    public function getAnnualAverageAttribute(): float
    {
        $t1 = $this->trimester1_average;
        $t2 = $this->trimester2_average;
        $t3 = $this->trimester3_average;
        
        $count = 0;
        $total = 0;
        
        if ($t1 > 0) {
            $total += $t1;
            $count++;
        }
        if ($t2 > 0) {
            $total += $t2;
            $count++;
        }
        if ($t3 > 0) {
            $total += $t3;
            $count++;
        }
        
        return $count > 0 ? round($total / $count, 2) : 0;
    }
}
