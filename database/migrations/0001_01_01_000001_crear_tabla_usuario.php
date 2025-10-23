<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->timestamp('correo_verificado_en')->nullable();
            $table->string('contrasenia');
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('tokens_restablecimiento_contrasenia', function (Blueprint $table) {
            $table->string('correo')->primary();
            $table->string('token');
            $table->timestamp('creado_en')->nullable();
        });

        Schema::create('sesiones', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('usuario_id')->nullable()->index();
            $table->string('direccion_ip', 45)->nullable();
            $table->text('agente_usuario')->nullable();
            $table->longText('carga');
            $table->integer('ultima_actividad')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('tokens_restablecimiento_contrasenia');
        Schema::dropIfExists('sesiones');
    }
};

