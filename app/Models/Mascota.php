<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    use HasFactory;

    protected $table = 'mascota';

    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'sexo',
        'fecha_nacimiento',
        'peso',
        'color',
        'marcas_distintivas',
        'numero_microchip',
        'esterilizado',
        'alergias',
        'condiciones_medicas',
        'ruta_foto',
        'id_propietario',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'peso' => 'decimal:2',
        'esterilizado' => 'boolean',
        'activo' => 'boolean',
    ];

    public function propietario()
    {
        return $this->belongsTo(Propietario::class, 'id_propietario');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_mascota');
    }

    public function fichasClinicas()
    {
        return $this->hasMany(FichaClinica::class, 'id_mascota');
    }

    public function vacunas()
    {
        return $this->hasMany(Vacuna::class, 'id_mascota');
    }

    public function desparasitaciones()
    {
        return $this->hasMany(Desparasitacion::class, 'id_mascota');
    }
}

