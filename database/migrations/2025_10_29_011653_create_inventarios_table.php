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
        Schema::create("inventarios", function (Blueprint $table) {
            $table->id();
            $table->string("codigo")->unique();
            $table->enum("tipoMovimiento", ["entrada", "salida"]);
            $table->date("fechaRegistro");
            $table->integer("cantidadProductos");
            $table->string("cedulaUsuario", 10);
            $table->foreign("cedulaUsuario")
                ->references("cedula")
                ->on("usuarios")
                ->onDelete("restrict")
                ->onUpdate("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("inventarios");
    }
};
