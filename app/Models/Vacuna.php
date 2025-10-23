<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    use HasFactory;

    protected $table = 'vacuna';

    protected $fillable = [
        'id_mascota',
        'id_veterinario',
        'id_ficha_clinica',
        'tipo',
        'nombre_vacuna',
        'marca',
        'numero_lote',
        'fecha_aplicacion',
        'fecha_expiracion',
        'fecha_proxima_dosis',
        'observaciones',
        'peso_aplicacion',
        'reacciones_adversas',
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
        'fecha_expiracion' => 'date',
        'fecha_proxima_dosis' => 'date',
        'peso_aplicacion' => 'decimal:2',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota');
    }

    public function veterinario()
    {
        return $this->belongsTo(Usuario::class, 'id_veterinario');
    }

    public function fichaClinica()
    {
        return $this->belongsTo(FichaClinica::class, 'id_ficha_clinica');
    }
}

