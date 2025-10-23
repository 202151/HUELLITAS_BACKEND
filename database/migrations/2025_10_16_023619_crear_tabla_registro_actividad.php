<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_actividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuario')->onDelete('set null');
            $table->string('accion');
            $table->string('tipo_modelo')->nullable();
            $table->unsignedBigInteger('id_modelo')->nullable();
            $table->json('valores_anteriores')->nullable();
            $table->json('valores_nuevos')->nullable();
            $table->string('direccion_ip')->nullable();
            $table->text('agente_usuario')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            $table->index(['usuario_id', 'created_at']);
            $table->index(['tipo_modelo', 'id_modelo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_actividad');
    }
};

