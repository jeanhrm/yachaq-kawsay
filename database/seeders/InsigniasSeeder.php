<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insignia;

class InsigniasSeeder extends Seeder
{
    public function run(): void
    {
        $insignias = [
            // Misiones completadas
            [
                'nombre'          => 'Guardián del agua',
                'nombre_quechua'  => 'Yaku Qawaq',
                'descripcion'     => 'Completa la Misión 1 con puntaje mínimo de 70 XP.',
                'emoji'           => '💧',
                'categoria'       => 'mision',
                'condicion'       => 'mision_completada',
                'valor_condicion' => 1,
            ],
            [
                'nombre'          => 'Sabio de la tierra',
                'nombre_quechua'  => 'Allpa Yachaq',
                'descripcion'     => 'Completa la Misión 2 con puntaje mínimo de 70 XP.',
                'emoji'           => '🥔',
                'categoria'       => 'mision',
                'condicion'       => 'mision_completada',
                'valor_condicion' => 2,
            ],
            // Habilidades
            [
                'nombre'          => 'Preguntador fuerte',
                'nombre_quechua'  => 'Tapuq Sinchi',
                'descripcion'     => 'Recibe nivel Logrado en 3 fases de Problematización.',
                'emoji'           => '❓',
                'categoria'       => 'habilidad',
                'condicion'       => 'fases_logradas',
                'valor_condicion' => 3,
            ],
            [
                'nombre'          => 'Mente fuerte',
                'nombre_quechua'  => 'Yuyay Sinchi',
                'descripcion'     => 'Recibe nivel Logrado en 3 fases de Hipótesis.',
                'emoji'           => '🌟',
                'categoria'       => 'habilidad',
                'condicion'       => 'hipotesis_logradas',
                'valor_condicion' => 3,
            ],
            [
                'nombre'          => 'El que tiene palabras',
                'nombre_quechua'  => 'Rimayniyuq',
                'descripcion'     => 'Presenta conclusiones logradas en 2 misiones.',
                'emoji'           => '📊',
                'categoria'       => 'habilidad',
                'condicion'       => 'conclusiones_logradas',
                'valor_condicion' => 2,
            ],
            // Constancia
            [
                'nombre'          => 'Persona de trabajo colectivo',
                'nombre_quechua'  => 'Minka Runa',
                'descripcion'     => 'Completa al menos 5 fases en total.',
                'emoji'           => '⚡',
                'categoria'       => 'constancia',
                'condicion'       => 'fases_completadas',
                'valor_condicion' => 5,
            ],
            [
                'nombre'          => 'Sol y luna',
                'nombre_quechua'  => 'Inti Killa',
                'descripcion'     => 'Completa ambas misiones.',
                'emoji'           => '🔥',
                'categoria'       => 'constancia',
                'condicion'       => 'misiones_completadas',
                'valor_condicion' => 2,
            ],
            [
                'nombre'          => 'Persona del cóndor',
                'nombre_quechua'  => 'Kuntur Runa',
                'descripcion'     => 'Alcanza nivel Apu Yachaq con todas las insignias desbloqueadas.',
                'emoji'           => '🦅',
                'categoria'       => 'constancia',
                'condicion'       => 'xp_total',
                'valor_condicion' => 700,
            ],
        ];

        foreach ($insignias as $insignia) {
            Insignia::create($insignia);
        }
    }
}