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
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('veterinarian_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('medical_record_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['vacuna', 'desparasitacion']);
            $table->string('name'); // nombre de la vacuna o desparasitante
            $table->string('brand')->nullable(); // marca del producto
            $table->string('batch_number')->nullable(); // número de lote
            $table->date('application_date');
            $table->date('expiration_date')->nullable();
            $table->date('next_dose_date')->nullable(); // fecha de próxima dosis
            $table->text('notes')->nullable();
            $table->decimal('weight_at_application', 5, 2)->nullable(); // peso al momento de aplicación
            $table->text('adverse_reactions')->nullable(); // reacciones adversas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccinations');
    }
};
