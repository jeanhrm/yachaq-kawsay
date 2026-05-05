<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progreso_estudiante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mision_id')->constrained('misiones')->cascadeOnDelete();
            $table->foreignId('fase_actual_id')->nullable()->constrained('fases_mision')->nullOnDelete();
            $table->integer('xp_ganado')->default(0);
            $table->integer('nivel_evaluacion')->default(0);
            $table->boolean('completada')->default(false);
            $table->timestamp('iniciada_en')->nullable();
            $table->timestamp('completada_en')->nullable();
            $table->unique(['user_id', 'mision_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progreso_estudiante');
    }
};