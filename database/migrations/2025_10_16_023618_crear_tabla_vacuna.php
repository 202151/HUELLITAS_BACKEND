<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacuna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mascota')->constrained('mascota')->onDelete('cascade');
            $table->foreignId('id_veterinario')->constrained('usuario')->onDelete('cascade');
            $table->foreignId('id_ficha_clinica')->nullable()->constrained('fichas_clinicas')->onDelete('set null');
            $table->enum('tipo', ['vacuna', 'desparasitacion']);
            $table->string('nombre_vacuna', 100);
            $table->string('marca')->nullable();
            $table->string('numero_lote')->nullable();
            $table->date('fecha_aplicacion');
            $table->date('fecha_expiracion')->nullable();
            $table->date('fecha_proxima_dosis')->nullable();
            $table->text('observaciones')->nullable();
            $table->decimal('peso_aplicacion', 5, 2)->nullable();
            $table->text('reacciones_adversas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacuna');
    }
};

