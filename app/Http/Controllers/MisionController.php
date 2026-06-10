<?php

namespace App\Http\Controllers;

use App\Models\Mision;
use App\Models\FaseMision;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MisionController extends Controller
{
    public function index()
    {
        $misiones = Mision::withCount('fases')->orderBy('orden')->get();
        return view('docente.misiones', compact('misiones'));
    }

    public function crear(Request $request)
    {
        $request->validate([
            'titulo'                 => 'required|string|max:200',
            'descripcion'            => 'required|string',
            'contexto_andino'        => 'required|string',
            'pregunta_investigacion' => 'required|string|max:300',
        ]);

        $mision = Mision::create([
            'titulo'                 => $request->titulo,
            'slug'                   => Str::slug($request->titulo),
            'descripcion'            => $request->descripcion,
            'contexto_andino'        => $request->contexto_andino,
            'pregunta_investigacion' => $request->pregunta_investigacion,
            'orden'                  => Mision::max('orden') + 1,
            'activa'                 => true,
        ]);

        // Crear las 5 fases estándar automáticamente
        $fases = [
            ['nombre' => 'Problematización', 'nombre_quechua' => 'Tapukuy',      'orden' => 1, 'xp_recompensa' => 15],
            ['nombre' => 'Hipótesis',         'nombre_quechua' => 'Yuyaychakuy', 'orden' => 2, 'xp_recompensa' => 20],
            ['nombre' => 'Recojo de datos',   'nombre_quechua' => 'Hap\'iy',     'orden' => 3, 'xp_recompensa' => 25],
            ['nombre' => 'Análisis',          'nombre_quechua' => 'Yachaqay',    'orden' => 4, 'xp_recompensa' => 25],
            ['nombre' => 'Conclusión',        'nombre_quechua' => 'Tukuchiy',    'orden' => 5, 'xp_recompensa' => 30],
        ];

        foreach ($fases as $fase) {
            FaseMision::create([
                'mision_id'      => $mision->id,
                'nombre'         => $fase['nombre'],
                'nombre_quechua' => $fase['nombre_quechua'],
                'instruccion'    => 'El docente debe completar esta instrucción.',
                'pista_tupaq'    => 'Tupaq dará pistas contextualizadas automáticamente.',
                'orden'          => $fase['orden'],
                'xp_recompensa'  => $fase['xp_recompensa'],
            ]);
        }

        return back()->with('success', '¡Misión creada con las 5 fases de indagación!');
    }

    public function eliminar(Mision $mision)
    {
        $mision->delete();
        return back()->with('success', 'Misión eliminada.');
    }
}