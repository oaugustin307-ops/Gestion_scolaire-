<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'name',
        'code',
        'coefficient',
    ];

    protected $casts = [
        'coefficient' => 'decimal:2',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
