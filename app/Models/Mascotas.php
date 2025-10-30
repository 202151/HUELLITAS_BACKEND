<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mascotas extends Model
{
     use HasFactory;

    protected $table = 'mascota';
    protected $primaryKey = 'id_mascota';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'raza',
        'edad',
        'id_propietario'
    ];

    // Relación con Propietario
    public function propietario()
    {
        return $this->belongsTo(Propietario::class, 'id_propietario', 'id_propietario');
    }
}
