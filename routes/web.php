<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

// Rutas autenticadas
Route::middleware(['auth'])->group(function () {

    // Dashboard según rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas solo docente
    Route::middleware(['role:docente'])->prefix('docente')->name('docente.')->group(function () {
        Route::get('/aulas', [DashboardController::class, 'aulas'])->name('aulas');
        Route::post('/aulas', [DashboardController::class, 'crearAula'])->name('aulas.crear');
        Route::get('/dashboard', [DashboardController::class, 'dashboardDocente'])->name('dashboard');
    });

    // Rutas solo estudiante
    Route::middleware(['role:estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
        Route::get('/misiones', [DashboardController::class, 'misiones'])->name('misiones');
    });
    Route::get('/misiones/{mision}', [DashboardController::class, 'jugarMision'])->name('estudiante.mision.jugar');
    Route::get('/perfil', [DashboardController::class, 'perfil'])->name('estudiante.perfil');
});

require __DIR__.'/auth.php';