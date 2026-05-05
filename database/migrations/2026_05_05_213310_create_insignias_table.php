<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insignias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('nombre_quechua');
            $table->text('descripcion');
            $table->string('emoji');
            $table->string('categoria'); // mision, habilidad, constancia
            $table->string('condicion'); // clave para evaluar el desbloqueo
            $table->integer('valor_condicion')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insignias');
    }
};