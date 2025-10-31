<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pdfcitas extends Model
{
    protected $table = 'citas';
    
    protected $fillable = [
        'mascota_id',
        'veterinario_id',
        'fecha',
        'hora',
        'motivo',
        'sintomas',
        'diagnostico',
        'tratamiento',
        'observaciones',
        'proxima_cita',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'date',
        'proxima_cita' => 'date',
    ];

    // Relaciones
    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function veterinario()
    {
        return $this->belongsTo(Veterinario::class);
    }
}