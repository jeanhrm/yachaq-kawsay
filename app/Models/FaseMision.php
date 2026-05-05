<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaseMision extends Model
{
    protected $table = 'fases_mision';
    protected $fillable = [
        'mision_id',
        'nombre',
        'nombre_quechua',
        'instruccion',
        'pista_tupaq',
        'orden',
        'xp_recompensa',
    ];

    public function mision()
    {
        return $this->belongsTo(Mision::class);
    }

    public function interacciones()
    {
        return $this->hasMany(InteraccionIA::class, 'fase_id');
    }
}