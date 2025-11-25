<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Egresos;
use App\Models\Productos;
use App\Models\Inventarios;

echo "========================================" . PHP_EOL;
echo "CORRIGIENDO CANTIDADES DE EGRESOS" . PHP_EOL;
echo "========================================" . PHP_EOL . PHP_EOL;

// Obtener todos los egresos
$egresos = Egresos::all();

echo "Total egresos: " . $egresos->count() . PHP_EOL . PHP_EOL;

foreach ($egresos as $egreso) {
    echo "Procesando Egreso ID {$egreso->id}: {$egreso->cantidad} unidades" . PHP_EOL;
    
    // Actualizar producto (restar)
    $producto = Productos::find($egreso->idProducto);
    if ($producto) {
        $cantidadAntes = $producto->cantidad;
        $producto->cantidad -= $egreso->cantidad;
        $producto->save();
        echo "  ✓ Producto '{$producto->nombre}': {$cantidadAntes} -> {$producto->cantidad} unidades" . PHP_EOL;
    }
    
    // Actualizar inventario (restar)
    $inventario = Inventarios::where('codigo', $egreso->codigoInventario)->first();
    if ($inventario) {
        $cantidadAntes = $inventario->cantidadProductos;
        $inventario->cantidadProductos -= $egreso->cantidad;
        $inventario->save();
        echo "  ✓ Inventario '{$inventario->codigo}': {$cantidadAntes} -> {$inventario->cantidadProductos} unidades" . PHP_EOL;
    }
    echo PHP_EOL;
}

echo "========================================" . PHP_EOL;
echo "✓ CORRECCIÓN COMPLETADA" . PHP_EOL;
echo "========================================" . PHP_EOL;
