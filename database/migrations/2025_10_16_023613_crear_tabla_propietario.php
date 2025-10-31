<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propietario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 150);
            $table->string('tipo_documento')->default('DNI');
            $table->string('numero_documento')->unique();
            $table->string('numero_cell', 30);
            $table->string('correo', 150)->nullable();
            $table->string('direccion', 250)->nullable();
            $table->string('ciudad')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F', 'Otro'])->nullable();
            $table->text('notas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propietario');
    }
};

