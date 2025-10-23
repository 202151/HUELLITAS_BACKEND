<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuario';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasenia',
        'rol_id',
        'activo',
    ];

    protected $hidden = [
        'contrasenia',
        'remember_token',
    ];

    protected $casts = [
        'correo_verificado_en' => 'datetime',
        'contrasenia' => 'hashed',
        'activo' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->contrasenia;
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function citasComoVeterinario()
    {
        return $this->hasMany(Cita::class, 'id_veterinario');
    }

    public function citasComoRecepcionista()
    {
        return $this->hasMany(Cita::class, 'id_recepcionista');
    }

    public function fichasClinicas()
    {
        return $this->hasMany(FichaClinica::class, 'id_veterinario');
    }

    public function vacunas()
    {
        return $this->hasMany(Vacuna::class, 'id_veterinario');
    }

    public function registrosActividad()
    {
        return $this->hasMany(RegistroActividad::class, 'usuario_id');
    }
}

