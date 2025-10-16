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
        'vaccine_name',
        'vaccine_type',
        'manufacturer',
        'batch_number',
        'vaccination_date',
        'next_vaccination_date',
        'notes',
    ];

    protected $casts = [
        'vaccination_date' => 'date',
        'next_vaccination_date' => 'date',
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
