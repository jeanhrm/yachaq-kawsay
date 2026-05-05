<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Aula extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'institucion',
        'docente_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($aula) {
            $aula->codigo = strtoupper(Str::random(6));
        });
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function estudiantes()
    {
        return $this->hasMany(User::class, 'aula_id');
    }
}