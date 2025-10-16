<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'service_id',
        'veterinarian_id',
        'receptionist_id',
        'appointment_date',
        'appointment_time',
        'status',
        'reason',
        'notes',
        'total_amount',
        'payment_status',
        'payment_method',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }

    public function receptionist()
    {
        return $this->belongsTo(User::class, 'receptionist_id');
    }
}
