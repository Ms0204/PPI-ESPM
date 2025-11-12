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
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->string("tituloReporte");
            $table->text("descripcion");
            $table->date("fechaEmision");
            $table->timestamps();
        });

        // Establecer el contador inicial para que el siguiente ID sea 100 (visualmente 0100)
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'sqlite') {
            // En SQLite actualizamos sqlite_sequence
            // Nota: sqlite_sequence existe cuando hay tablas AUTOINCREMENT; aseguramos eliminación/insert
            DB::statement("DELETE FROM sqlite_sequence WHERE name='reportes'");
            DB::statement("INSERT INTO sqlite_sequence(name, seq) VALUES('reportes', 99)");
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE reportes AUTO_INCREMENT = 100');
        } elseif ($driver === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('reportes','id'), 99, false)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
