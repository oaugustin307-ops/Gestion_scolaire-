<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'level',
        'school_fees',
    ];

    protected $casts = [
        'school_fees' => 'decimal:2',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'class_id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'class_id');
    }
}
