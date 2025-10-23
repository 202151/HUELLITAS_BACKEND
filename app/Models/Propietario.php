<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    use HasFactory;

    protected $table = 'propietario';

    protected $fillable = [
        'nombre_completo',
        'tipo_documento',
        'numero_documento',
        'numero_cell',
        'correo',
        'direccion',
        'ciudad',
        'fecha_nacimiento',
        'sexo',
        'notas',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    public function mascotas()
    {
        return $this->hasMany(Mascota::class, 'id_propietario');
    }
}

