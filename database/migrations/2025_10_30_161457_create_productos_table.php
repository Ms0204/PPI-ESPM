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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            // cantidad solo dígitos: integer
            $table->integer('cantidad');
            $table->timestamps();
        });

        // Establecer el contador inicial para que el siguiente ID sea 10 (visualmente 010)
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'sqlite') {
            DB::statement("DELETE FROM sqlite_sequence WHERE name='productos'");
            DB::statement("INSERT INTO sqlite_sequence(name, seq) VALUES('productos', 9)");
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE productos AUTO_INCREMENT = 10');
        } elseif ($driver === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('productos','id'), 9, false)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
