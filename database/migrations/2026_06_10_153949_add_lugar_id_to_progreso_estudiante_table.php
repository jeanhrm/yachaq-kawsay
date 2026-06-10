<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progreso_estudiante', function (Blueprint $table) {
            $table->foreignId('lugar_id')->nullable()->constrained('lugares')->nullOnDelete()->after('mision_id');
        });
    }

    public function down(): void
    {
        Schema::table('progreso_estudiante', function (Blueprint $table) {
            $table->dropForeign(['lugar_id']);
            $table->dropColumn('lugar_id');
        });
    }
};