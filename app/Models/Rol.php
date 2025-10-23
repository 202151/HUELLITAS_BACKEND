<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'nombre_mostrar',
        'descripcion',
        'permisos',
    ];

    protected $casts = [
        'permisos' => 'array',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'rol_id');
    }
}

