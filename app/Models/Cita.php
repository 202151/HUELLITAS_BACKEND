<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'id_mascota',
        'id_servicio',
        'id_veterinario',
        'id_recepcionista',
        'fecha_cita',
        'duracion_minutos',
        'estado',
        'motivo',
        'notas',
        'monto_total',
        'confirmada_en',
        'iniciada_en',
        'completada_en',
        'razon_cancelacion',
    ];

    protected $casts = [
        'fecha_cita' => 'datetime',
        'monto_total' => 'decimal:2',
        'confirmada_en' => 'datetime',
        'iniciada_en' => 'datetime',
        'completada_en' => 'datetime',
        'duracion_minutos' => 'integer',
    ];

    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function veterinario()
    {
        return $this->belongsTo(Usuario::class, 'id_veterinario');
    }

    public function recepcionista()
    {
        return $this->belongsTo(Usuario::class, 'id_recepcionista');
    }

    public function fichaClinica()
    {
        return $this->hasOne(FichaClinica::class, 'id_cita');
    }
}

