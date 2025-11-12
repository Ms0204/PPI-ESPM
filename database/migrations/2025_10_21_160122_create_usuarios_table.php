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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('cedula', 10)->unique(); // tipo string para permitir ceros delante
            $table->string('usuario')->nullable(); // puede ser nulo
            $table->string('contrasenia')->nullable(); // puede ser nulo
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('correo')->unique();
            $table->string('direccion');
            $table->string('telefono', 15); // tipo string para permitir ceros iniciales
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
