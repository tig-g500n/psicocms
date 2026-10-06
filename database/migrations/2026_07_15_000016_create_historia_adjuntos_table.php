<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historia_adjuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrada_id')->constrained('historia_entradas')->cascadeOnDelete();
            $table->string('ruta');
            $table->string('nombre');
            $table->enum('tipo', ['imagen', 'pdf']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historia_adjuntos');
    }
};
