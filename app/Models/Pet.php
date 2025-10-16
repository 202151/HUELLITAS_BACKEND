<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'species',
        'breed',
        'gender',
        'birth_date',
        'weight',
        'color',
        'distinctive_marks',
        'microchip_number',
        'is_sterilized',
        'allergies',
        'medical_conditions',
        'photo_path',
        'owner_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'weight' => 'decimal:2',
        'is_sterilized' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the owner that owns the pet.
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Get the appointments for the pet.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the medical records for the pet.
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    /**
     * Get the vaccinations for the pet.
     */
    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }

    /**
     * Get the age of the pet in years.
     */
    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->diffInYears(now()) : null;
    }
}
