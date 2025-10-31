<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaClinica extends Model
{
    use HasFactory;

    protected $table = 'fichas_clinicas';

    protected $fillable = [
        'id_mascota',
        'id_cita',
        'id_veterinario',
        'fecha_visita',
        'motivo',
        'sintomas',
        'examen_fisico',
        'peso',
        'temperatura',
        'diagnostico',
        'tratamiento',
        'medicamentos',
        'recomendaciones',
        'fecha_proxima_visita',
        'adjuntos',
        'notas',
    ];

    protected $casts = [
        'fecha_visita' => 'date',
        'peso' => 'decimal:2',
        'temperatura' => 'decimal:1',
        'fecha_proxima_visita' => 'date',
        'adjuntos' => 'array',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota');
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita');
    }

    public function veterinario()
    {
        return $this->belongsTo(Usuario::class, 'id_veterinario');
    }

    public function vacunas()
    {
        return $this->hasMany(Vacuna::class, 'id_ficha_clinica');
    }
}

