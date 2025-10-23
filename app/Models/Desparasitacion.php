<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desparasitacion extends Model
{
    use HasFactory;

    protected $table = 'desparasitaciones';

    protected $fillable = [
        'id_mascota',
        'tipo_producto',
        'fecha_aplicacion',
        'proxima_aplicacion',
        'via_administracion',
        'observaciones',
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
        'proxima_aplicacion' => 'date',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota');
    }
}

