<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mascota', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('especie');
            $table->string('raza', 50)->nullable();
            $table->enum('sexo', ['M', 'F']);
            $table->date('fecha_nacimiento')->nullable();
            $table->decimal('peso', 5, 2)->nullable();
            $table->string('color')->nullable();
            $table->text('marcas_distintivas')->nullable();
            $table->string('numero_microchip')->nullable()->unique();
            $table->boolean('esterilizado')->default(false);
            $table->text('alergias')->nullable();
            $table->text('condiciones_medicas')->nullable();
            $table->string('ruta_foto')->nullable();
            $table->foreignId('id_propietario')->constrained('propietario')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mascota');
    }
};

