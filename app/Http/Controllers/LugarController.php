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
        return view('docente.lugares', compact('lugares'));
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
}