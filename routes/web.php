<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\MisionController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

// Ruta pública para QR — fuera del grupo auth
Route::get('/lugar/{slug}', [LugarController::class, 'escanear'])->name('lugar.escanear');

// Ruta pública para generar imagen QR
Route::get('/qr/{slug}', function($slug) {
    $url = url("/lugar/{$slug}");
    $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
        ->size(200)
        ->margin(1)
        ->errorCorrection('H')
        ->generate($url);
    return response($qr)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'public, max-age=3600');
})->name('qr.generar');

// Rutas autenticadas
Route::middleware(['auth'])->group(function () {

    // Dashboard según rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Ranking — visible para todos los roles
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');

    // Rutas solo docente
    Route::middleware(['role:docente'])->prefix('docente')->name('docente.')->group(function () {
        Route::get('/aulas', [DashboardController::class, 'aulas'])->name('aulas');
        Route::post('/aulas', [DashboardController::class, 'crearAula'])->name('aulas.crear');
        Route::get('/dashboard', [DashboardController::class, 'dashboardDocente'])->name('dashboard');
        Route::get('/lugares', [LugarController::class, 'index'])->name('lugares');
        Route::get('/lugares/{lugar}/qr', [LugarController::class, 'qr'])->name('lugares.qr');
        Route::post('/lugares', [LugarController::class, 'crear'])->name('lugares.crear');
        Route::delete('/lugares/{lugar}', [LugarController::class, 'eliminar'])->name('lugares.eliminar');
        Route::get('/misiones', [MisionController::class, 'index'])->name('misiones');
        Route::post('/misiones', [MisionController::class, 'crear'])->name('misiones.crear');
        Route::delete('/misiones/{mision}', [MisionController::class, 'eliminar'])->name('misiones.eliminar');
    });

    // Rutas estudiante
    Route::middleware(['role:estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
        Route::get('/misiones', [DashboardController::class, 'misiones'])->name('misiones');
        Route::get('/perfil', [DashboardController::class, 'perfil'])->name('perfil');
        Route::get('/perfil/editar', [DashboardController::class, 'editarPerfil'])->name('perfil.editar');
        Route::put('/perfil/editar', [DashboardController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    });

    // Jugar misión — accesible para estudiantes sin prefijo
    Route::middleware(['role:estudiante'])->get('/misiones/{mision:slug}', [DashboardController::class, 'jugarMision'])->name('estudiante.mision.jugar');

});

require __DIR__.'/auth.php';