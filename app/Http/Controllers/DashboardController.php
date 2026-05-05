<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isDocente()) {
            return redirect()->route('docente.aulas');
        }

        return redirect()->route('estudiante.misiones');
    }

    public function aulas()
    {
        $aulas = auth()->user()->aulas()->withCount('estudiantes')->get();
        return view('docente.aulas', compact('aulas'));
    }

    public function crearAula(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'institucion' => 'nullable|string|max:150',
        ]);

        auth()->user()->aulas()->create([
            'nombre' => $request->nombre,
            'institucion' => $request->institucion,
        ]);

        return back()->with('success', '¡Aula creada correctamente!');
    }

    public function misiones()
    {
        return view('estudiante.misiones');
    }
    public function jugarMision(\App\Models\Mision $mision)
    {
        return view('estudiante.jugar', compact('mision'));
    }
    public function perfil()
    {
        $user = auth()->user()->load('insignias', 'progresos.mision');
        $todasInsignias = \App\Models\Insignia::all();
        return view('estudiante.perfil', compact('user', 'todasInsignias'));
    }
}