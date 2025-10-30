<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'veterinarian_id',
        'medical_record_id',
        'type',
        'name',
        'brand',
        'batch_number',
        'application_date',
        'expiration_date',
        'next_dose_date',
        'weight_at_application',
        'adverse_reactions',
        'notes',
    ];

    protected $casts = [
        'application_date' => 'date',
        'expiration_date' => 'date',
        'next_dose_date' => 'date',
        'weight_at_application' => 'decimal:2',
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
