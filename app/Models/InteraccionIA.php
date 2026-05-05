<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InteraccionIA extends Model
{
    protected $table = 'interacciones_ia';
    protected $fillable = [
        'user_id',
        'mision_id',
        'fase_id',
        'respuesta_estudiante',
        'respuesta_tupaq',
        'nivel_logrado',
        'evaluacion_competencias',
        'fase_aprobada',
    ];

    protected $casts = [
        'evaluacion_competencias' => 'array',
        'fase_aprobada'           => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mision()
    {
        return $this->belongsTo(Mision::class);
    }

    public function fase()
    {
        return $this->belongsTo(FaseMision::class, 'fase_id');
    }
}