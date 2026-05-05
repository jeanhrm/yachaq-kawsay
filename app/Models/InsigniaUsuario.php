<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsigniaUsuario extends Model
{
    protected $fillable = [
        'user_id',
        'insignia_id',
        'desbloqueada_en',
    ];

    protected $casts = [
        'desbloqueada_en' => 'datetime',
    ];
}