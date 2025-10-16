<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'veterinarian_id',
        'visit_date',
        'reason',
        'symptoms',
        'diagnosis',
        'treatment',
        'medications',
        'recommendations',
        'next_visit_date',
        'weight',
        'temperature',
        'heart_rate',
        'respiratory_rate',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_visit_date' => 'date',
        'weight' => 'decimal:2',
        'temperature' => 'decimal:1',
        'heart_rate' => 'integer',
        'respiratory_rate' => 'integer',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }
}
