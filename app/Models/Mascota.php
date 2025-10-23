<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    use HasFactory;

    protected $table = 'mascota';
    protected $primaryKey = 'id_mascota';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'raza',
        'edad',
        'id_propietario',
    ];

    public function desparasitaciones()
    {
        return $this->hasMany(Desparasitacion::class, 'id_mascota');
    }
}

