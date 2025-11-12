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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("descripcion");
            
            // idProducto: FK to productos.id
            $table->unsignedBigInteger('idProducto');
            $table->foreign('idProducto')
                ->references('id')
                ->on('productos')
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
        Schema::dropIfExists('categorias');
    }
};
