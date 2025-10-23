<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroActividad extends Model
{
    use HasFactory;

    protected $table = 'registro_actividad';

    protected $fillable = [
        'usuario_id',
        'accion',
        'tipo_modelo',
        'id_modelo',
        'valores_anteriores',
        'valores_nuevos',
        'direccion_ip',
        'agente_usuario',
        'descripcion',
    ];

    protected $casts = [
        'valores_anteriores' => 'array',
        'valores_nuevos' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

