<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Eliminar tabla temporal si existe de intentos anteriores
        Schema::dropIfExists('egresos_temp');
        
        // Crear tabla temporal con la estructura correcta
        Schema::create('egresos_temp', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->date('fechaEgreso');
            
            $table->unsignedBigInteger('idProducto');
            $table->foreign('idProducto')
                ->references('id')
                ->on('productos')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            
            $table->string('codigoInventario');
            $table->foreign('codigoInventario')
                ->references('codigo')
                ->on('inventarios')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
        
        // Copiar datos convirtiendo el ID a código
        $hasObservacion = Schema::hasColumn('egresos', 'observacion');
        
        if ($hasObservacion) {
            DB::statement('
                INSERT INTO egresos_temp (id, cantidad, fechaEgreso, idProducto, codigoInventario, observacion, created_at, updated_at)
                SELECT e.id, e.cantidad, e.fechaEgreso, e.idProducto, i.codigo, e.observacion, e.created_at, e.updated_at
                FROM egresos e
                LEFT JOIN inventarios i ON e.codigoInventario = i.id
            ');
        } else {
            DB::statement('
                INSERT INTO egresos_temp (id, cantidad, fechaEgreso, idProducto, codigoInventario, created_at, updated_at)
                SELECT e.id, e.cantidad, e.fechaEgreso, e.idProducto, i.codigo, e.created_at, e.updated_at
                FROM egresos e
                LEFT JOIN inventarios i ON e.codigoInventario = i.id
            ');
        }
        
        // Eliminar tabla original
        Schema::dropIfExists('egresos');
        
        // Renombrar tabla temporal
        Schema::rename('egresos_temp', 'egresos');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('egresos', function (Blueprint $table) {
            // Eliminar la llave foránea
            $table->dropForeign(['codigoInventario']);
            
            // Eliminar la columna
            $table->dropColumn('codigoInventario');
        });

        Schema::table('egresos', function (Blueprint $table) {
            // Restaurar la columna como unsignedBigInteger
            $table->unsignedBigInteger('codigoInventario')->after('idProducto');
            
            // Restaurar la llave foránea al id
            $table->foreign('codigoInventario')
                ->references('id')
                ->on('inventarios')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }
};
