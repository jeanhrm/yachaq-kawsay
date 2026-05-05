<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insignia extends Model
{
    protected $fillable = [
        'nombre',
        'nombre_quechua',
        'descripcion',
        'emoji',
        'categoria',
        'condicion',
        'valor_condicion',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'insignia_usuario')
            ->withPivot('desbloqueada_en')
            ->withTimestamps();
    }
}