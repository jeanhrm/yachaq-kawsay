<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresoEstudiante extends Model
{
    
    protected $table = 'progreso_estudiante';
    protected $fillable = [
        'user_id',
        'mision_id',
        'fase_actual_id',
        'xp_ganado',
        'nivel_evaluacion',
        'completada',
        'iniciada_en',
        'completada_en',
    ];

    protected $casts = [
        'completada'    => 'boolean',
        'iniciada_en'   => 'datetime',
        'completada_en' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mision()
    {
        return $this->belongsTo(Mision::class);
    }

    public function faseActual()
    {
        return $this->belongsTo(FaseMision::class, 'fase_actual_id');
    }
}