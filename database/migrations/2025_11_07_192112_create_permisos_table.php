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
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fechaAsignacion');
            $table->string('estado');

            // cedulaUsuario: FK to usuarios.cedula (string length 10)
            $table->string('cedulaUsuario', 10);
            $table->foreign('cedulaUsuario')
                ->references('cedula')
                ->on('usuarios')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            // idRol: FK to roles.id
            $table->unsignedBigInteger('idRol');
            $table->foreign('idRol')
                ->references('id')
                ->on('roles')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
