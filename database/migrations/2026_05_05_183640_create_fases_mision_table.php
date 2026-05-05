<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fases_mision', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mision_id')->constrained('misiones')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('nombre_quechua');
            $table->text('instruccion');
            $table->text('pista_tupaq');
            $table->integer('orden');
            $table->integer('xp_recompensa')->default(20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fases_mision');
    }
};