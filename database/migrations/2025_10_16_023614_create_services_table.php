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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['consulta', 'vacuna', 'baño', 'grooming', 'cirugia', 'emergencia', 'otros']);
            $table->decimal('price', 8, 2);
            $table->integer('duration_minutes')->default(30); // duración estimada en minutos
            $table->boolean('requires_appointment')->default(true);
            $table->text('requirements')->nullable(); // requisitos especiales
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
