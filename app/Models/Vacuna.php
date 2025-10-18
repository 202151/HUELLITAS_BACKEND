<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    use HasFactory;

    protected $table = 'vacuna';
    protected $primaryKey = 'id_vacuna';
    
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null; // No hay updated_at en la tabla

    protected $fillable = [
        'id_mascota',
        'nombre_vacuna',
        'fecha_aplicacion',
        'proxima_dosis',
        'observaciones',
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
        'proxima_dosis' => 'date',
        'creado_en' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Relación con Mascota
     */
    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'id_mascota', 'id_mascota');
    }

    /**
     * Scope para vacunas próximas a vencer
     */
    public function scopeProximasAVencer($query, $dias = 30)
    {
        return $query->whereNotNull('proxima_dosis')
                     ->whereBetween('proxima_dosis', [now(), now()->addDays($dias)]);
    }

    /**
     * Scope para vacunas de una mascota específica
     */
    public function scopePorMascota($query, $idMascota)
    {
        return $query->where('id_mascota', $idMascota);
    }
}
