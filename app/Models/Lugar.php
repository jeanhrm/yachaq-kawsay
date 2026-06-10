<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    
    protected $table = 'lugares';
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'ubicacion',
        'latitud',
        'longitud',
        'mision_id',
        'imagen',
        'activo',
    ];

    public function mision()
    {
        return $this->belongsTo(Mision::class);
    }

    public function progresos()
    {
        return $this->hasMany(ProgresoEstudiante::class);
    }
}