<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desparasitacion extends Model
{
    use HasFactory;

    protected $table = 'desparasitaciones';
    protected $primaryKey = 'id_desparasitaciones';
    public $timestamps = false;

    protected $fillable = [
        'id_mascota',
        'tipo_producto',
        'fecha_aplicacion',
        'proxima_aplicacion',
        'via_administracion',
        'observaciones',
        'creado_en'
    ];

    // Relación con Mascota
    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota');
    }
}
