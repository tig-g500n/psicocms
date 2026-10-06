<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfil_publico', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('eslogan')->nullable();
            $table->string('num_colegiado')->nullable();
            $table->string('telefono_citas')->nullable();
            $table->string('email_citas')->nullable();
            $table->text('sobre_mi')->nullable();
            $table->string('direccion')->nullable();
            $table->string('lugar_consulta')->nullable();
            $table->string('mapa_lat')->nullable();
            $table->string('mapa_lng')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfil_publico');
    }
};
