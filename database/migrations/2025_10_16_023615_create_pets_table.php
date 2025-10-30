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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('species'); // Perro, Gato, Ave, etc.
            $table->string('breed')->nullable();
            $table->enum('gender', ['M', 'F']);
            $table->date('birth_date')->nullable();
            $table->decimal('weight', 5, 2)->nullable(); // peso en kg
            $table->string('color')->nullable();
            $table->text('distinctive_marks')->nullable(); // marcas distintivas
            $table->string('microchip_number')->nullable()->unique();
            $table->boolean('is_sterilized')->default(false);
            $table->text('allergies')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->string('photo_path')->nullable();
            $table->foreignId('owner_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
