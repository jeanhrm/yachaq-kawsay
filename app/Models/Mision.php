<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mision extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'contexto_andino',
        'pregunta_investigacion',
        'orden',
        'imagen',
        'activa',
    ];

    public function fases()
    {
        return $this->hasMany(FaseMision::class)->orderBy('orden');
    }

    public function progresos()
    {
        return $this->hasMany(ProgresoEstudiante::class);
    }
}