<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre_servicio',
        'descripcion',
        'categoria',
        'precio',
        'duracion_estimada',
        'requiere_cita',
        'requisitos',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'duracion_estimada' => 'integer',
        'requiere_cita' => 'boolean',
        'activo' => 'boolean',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_servicio');
    }
}

