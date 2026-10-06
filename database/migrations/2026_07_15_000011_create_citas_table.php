<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('modalidad', ['online', 'presencial']);
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'realizada'])->default('pendiente');
            $table->enum('origen', ['publico', 'manual'])->default('manual');
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['fecha', 'modalidad']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
