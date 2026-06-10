<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LugarController;
use App\Http\Controllers\RankingController;

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
    $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
        ->size(200)
        ->margin(1)
        ->generate($url);
    return response($qr)->header('Content-Type', 'image/png');
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
    });

    // Rutas estudiante
    Route::middleware(['role:estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
        Route::get('/misiones', [DashboardController::class, 'misiones'])->name('misiones');
        Route::get('/perfil', [DashboardController::class, 'perfil'])->name('perfil');
    });

    // Jugar misión — accesible para estudiantes sin prefijo
    Route::middleware(['role:estudiante'])->get('/misiones/{mision:slug}', [DashboardController::class, 'jugarMision'])->name('estudiante.mision.jugar');

});

require __DIR__.'/auth.php';