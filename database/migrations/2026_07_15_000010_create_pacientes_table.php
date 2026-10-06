<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono')->unique();
            $table->string('email')->nullable();
            $table->text('motivo')->nullable();
            $table->text('notas')->nullable();
            $table->string('estado')->default('activo');
            $table->string('genero')->nullable();
            $table->enum('modalidad_pref', ['online', 'presencial'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
