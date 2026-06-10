<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nivel_educativo')->nullable()->after('aula_id'); // primaria, secundaria
            $table->integer('grado')->nullable()->after('nivel_educativo'); // 1-6 primaria, 1-5 secundaria
            $table->string('seccion')->nullable()->after('grado'); // A, B, C
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nivel_educativo', 'grado', 'seccion']);
        });
    }
};