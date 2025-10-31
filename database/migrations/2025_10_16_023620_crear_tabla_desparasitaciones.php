<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desparasitaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mascota')->constrained('mascota')->onDelete('cascade');
            $table->string('tipo_producto', 100);
            $table->date('fecha_aplicacion');
            $table->date('proxima_aplicacion')->nullable();
            $table->enum('via_administracion', ['oral', 'topica', 'inyectable']);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desparasitaciones');
    }
};

