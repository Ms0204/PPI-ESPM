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
        // 1. Eliminar la llave foránea idProducto de categorias
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropForeign(['idProducto']);
            $table->dropColumn('idProducto');
        });

        // 2. Agregar la llave foránea idCategoria a productos
        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedBigInteger('idCategoria')->nullable()->after('cantidad');
            $table->foreign('idCategoria')
                ->references('id')
                ->on('categorias')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir: eliminar idCategoria de productos
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['idCategoria']);
            $table->dropColumn('idCategoria');
        });

        // Revertir: volver a agregar idProducto a categorias
        Schema::table('categorias', function (Blueprint $table) {
            $table->unsignedBigInteger('idProducto');
            $table->foreign('idProducto')
                ->references('id')
                ->on('productos')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }
};
