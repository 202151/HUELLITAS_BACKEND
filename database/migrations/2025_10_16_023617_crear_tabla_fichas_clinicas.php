<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichas_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mascota')->constrained('mascota')->onDelete('cascade');
            $table->foreignId('id_cita')->nullable()->constrained('citas')->onDelete('set null');
            $table->foreignId('id_veterinario')->constrained('usuario')->onDelete('cascade');
            $table->date('fecha_visita');
            $table->text('motivo');
            $table->text('sintomas')->nullable();
            $table->text('examen_fisico')->nullable();
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('temperatura', 4, 1)->nullable();
            $table->text('diagnostico');
            $table->text('tratamiento');
            $table->text('medicamentos')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->date('fecha_proxima_visita')->nullable();
            $table->json('adjuntos')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichas_clinicas');
    }
};

