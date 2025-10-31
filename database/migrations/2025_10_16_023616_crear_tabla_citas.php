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
            $table->foreignId('id_mascota')->constrained('mascota')->onDelete('cascade');
            $table->foreignId('id_servicio')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('id_veterinario')->constrained('usuario')->onDelete('cascade');
            $table->foreignId('id_recepcionista')->nullable()->constrained('usuario')->onDelete('set null');
            $table->datetime('fecha_cita');
            $table->integer('duracion_minutos');
            $table->enum('estado', ['programada', 'confirmada', 'en_curso', 'completada', 'cancelada', 'no_asistio'])->default('programada');
            $table->text('motivo')->nullable();
            $table->text('notas')->nullable();
            $table->decimal('monto_total', 8, 2)->nullable();
            $table->datetime('confirmada_en')->nullable();
            $table->datetime('iniciada_en')->nullable();
            $table->datetime('completada_en')->nullable();
            $table->text('razon_cancelacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};

