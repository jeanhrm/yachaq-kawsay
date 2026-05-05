<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interacciones_ia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mision_id')->constrained('misiones')->cascadeOnDelete();
            $table->foreignId('fase_id')->constrained('fases_mision')->cascadeOnDelete();
            $table->text('respuesta_estudiante');
            $table->text('respuesta_tupaq');
            $table->integer('nivel_logrado')->default(0);
            $table->json('evaluacion_competencias')->nullable();
            $table->boolean('fase_aprobada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interacciones_ia');
    }
};