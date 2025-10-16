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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('veterinarian_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receptionist_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('appointment_date');
            $table->integer('duration_minutes');
            $table->enum('status', ['programada', 'confirmada', 'en_curso', 'completada', 'cancelada', 'no_asistio']);
            $table->text('reason')->nullable(); // motivo de la cita
            $table->text('notes')->nullable(); // notas adicionales
            $table->decimal('total_amount', 8, 2)->nullable();
            $table->datetime('confirmed_at')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
