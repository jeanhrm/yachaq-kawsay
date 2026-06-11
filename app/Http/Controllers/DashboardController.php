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

    public function dashboardDocente()
    {
        $aulas = auth()->user()->aulas()->with([
            'estudiantes.progresos.mision',
            'estudiantes.insignias',
        ])->withCount('estudiantes')->get();

        return view('docente.dashboard', compact('aulas'));
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
        $misiones = \App\Models\Mision::where('activa', true)
            ->orderBy('orden')
            ->get();

        $progresos = auth()->user()
            ->progresos()
            ->get()
            ->keyBy('mision_id');

        return view('estudiante.misiones', compact('misiones', 'progresos'));
    }
    public function jugarMision(\App\Models\Mision $mision, Request $request)
    {
        $lugarId = $request->query('lugar');
        return view('estudiante.jugar', compact('mision', 'lugarId'));
    }
    public function perfil()
    {
        $user = auth()->user()->load('insignias', 'progresos.mision');
        $todasInsignias = \App\Models\Insignia::all();
        return view('estudiante.perfil', compact('user', 'todasInsignias'));
    }
    public function editarPerfil()
    {
        return view('estudiante.editar-perfil');
    }

    public function actualizarPerfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'            => 'required|string|max:255',
            'institucion'     => 'nullable|string|max:200',
            'nivel_educativo' => 'nullable|in:primaria,secundaria',
            'grado'           => 'nullable|integer|min:1|max:6',
            'seccion'         => 'nullable|string|max:5',
            'password'        => 'nullable|min:8|confirmed',
        ]);

        $user->name            = $request->name;
        $user->institucion     = $request->institucion;
        $user->nivel_educativo = $request->nivel_educativo;
        $user->grado           = $request->grado;
        $user->seccion         = $request->seccion;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('estudiante.perfil')
            ->with('success', '¡Perfil actualizado correctamente!');
    }
}