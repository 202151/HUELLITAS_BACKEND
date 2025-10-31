<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_servicio', 100);
            $table->text('descripcion')->nullable();
            $table->enum('categoria', ['consulta', 'vacuna', 'baño', 'grooming', 'cirugia', 'emergencia', 'otros']);
            $table->decimal('precio', 10, 2);
            $table->integer('duracion_estimada')->default(30);
            $table->boolean('requiere_cita')->default(true);
            $table->text('requisitos')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};

