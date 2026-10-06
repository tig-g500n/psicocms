<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disponibilidad_config', function (Blueprint $table) {
            $table->id();
            $table->enum('modalidad', ['online', 'presencial'])->unique();
            $table->unsignedSmallInteger('duracion_min')->default(50);
            $table->unsignedSmallInteger('descanso_min')->default(10);
            $table->boolean('descanso_activo')->default(false);
            $table->time('hora_entrada')->default('09:00');
            $table->time('hora_salida')->default('18:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disponibilidad_config');
    }
};
