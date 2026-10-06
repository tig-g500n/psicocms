<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('imagenes_web', function (Blueprint $table) {
            $table->string('mime')->nullable()->after('ruta');
            $table->longText('contenido_base64')->nullable()->after('mime');
        });
    }

    public function down(): void
    {
        Schema::table('imagenes_web', function (Blueprint $table) {
            $table->dropColumn(['mime', 'contenido_base64']);
        });
    }
};
