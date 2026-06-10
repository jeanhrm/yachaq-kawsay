<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LugarController extends Controller
{
    // Estudiante escanea QR y llega aquí
    public function escanear(string $slug)
    {
        $lugar = Lugar::where('slug', $slug)
            ->where('activo', true)
            ->with('mision')
            ->firstOrFail();

        // Si no está logueado → guardar slug en sesión y redirigir a login
        if (!auth()->check()) {
            session(['lugar_pendiente' => $slug]);
            return redirect()->route('login')
                ->with('info', "Inicia sesión para comenzar la misión en {$lugar->nombre}");
        }

        // Si está logueado → redirigir a la misión con el lugar registrado
        return redirect()->route('estudiante.mision.jugar', [
            'mision' => $lugar->mision_id,
            'lugar'  => $lugar->id,
        ]);
    }

    // Dashboard de lugares para el docente
    public function index()
    {
        $lugares = Lugar::with('mision')->get();
        $misiones = \App\Models\Mision::where('activa', true)->orderBy('orden')->get();
        return view('docente.lugares', compact('lugares', 'misiones'));
    }

    // Generar QR de un lugar
    public function qr(Lugar $lugar)
    {
        $url = url("/lugar/{$lugar->slug}");

        $qr = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($url);

        return response($qr)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', "attachment; filename=qr-{$lugar->slug}.png");
    }
    public function crear(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:150',
            'ubicacion' => 'nullable|string|max:150',
            'descripcion' => 'nullable|string',
            'mision_id' => 'required|exists:misiones,id',
            'latitud'   => 'nullable|numeric',
            'longitud'  => 'nullable|numeric',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->nombre);
        $slugBase = $slug;
        $i = 1;
        while (\App\Models\Lugar::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $i++;
        }

        \App\Models\Lugar::create([
            'nombre'      => $request->nombre,
            'slug'        => $slug,
            'descripcion' => $request->descripcion,
            'ubicacion'   => $request->ubicacion,
            'mision_id'   => $request->mision_id,
            'latitud'     => $request->latitud,
            'longitud'    => $request->longitud,
            'activo'      => true,
        ]);

        return back()->with('success', '¡Lugar creado correctamente! Ya puedes descargar su QR.');
    }
    public function eliminar(Lugar $lugar)
    {
        $lugar->delete();
        return back()->with('success', 'Lugar eliminado correctamente.');
    }
}