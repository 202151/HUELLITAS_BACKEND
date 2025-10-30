<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('veterinarian_id')->constrained('users')->onDelete('cascade');
            $table->date('visit_date');
            $table->text('reason'); // motivo de la consulta
            $table->text('symptoms')->nullable(); // síntomas observados
            $table->text('physical_examination')->nullable(); // examen físico
            $table->decimal('weight', 5, 2)->nullable(); // peso en kg
            $table->decimal('temperature', 4, 1)->nullable(); // temperatura en °C
            $table->text('diagnosis'); // diagnóstico
            $table->text('treatment'); // tratamiento prescrito
            $table->text('medications')->nullable(); // medicamentos recetados
            $table->text('recommendations')->nullable(); // recomendaciones
            $table->date('next_visit_date')->nullable(); // próxima cita recomendada
            $table->json('attachments')->nullable(); // archivos adjuntos (rutas)
            $table->text('notes')->nullable(); // notas adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
