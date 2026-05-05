<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['docente', 'estudiante'])->default('estudiante')->after('email');
            $table->foreignId('aula_id')->nullable()->constrained('aulas')->nullOnDelete()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['aula_id']);
            $table->dropColumn(['role', 'aula_id']);
        });
    }
};