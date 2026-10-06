<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes_precios', function (Blueprint $table) {
            $table->id();
            $table->enum('modalidad', ['online', 'presencial']);
            $table->string('titulo');
            $table->string('precio')->nullable();
            $table->text('caracteristicas')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planes_precios');
    }
};
