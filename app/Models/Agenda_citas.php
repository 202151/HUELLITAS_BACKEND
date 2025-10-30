<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda_citas extends Model
{
    use HasFactory;

    protected $table = 'citas';
    protected $primaryKey = 'id_citas';

    protected $fillable = [
        'nombre_cliente',
        'servicio',
        'fecha',
        'estado',
        'notas'
    ];

    public $timestamps = false;

    protected $casts = [
        'fecha' => 'datetime',
        'estado' => 'string'
    ];
}