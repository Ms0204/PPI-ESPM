<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Ingresos;
use App\Models\Productos;
use App\Models\Inventarios;

echo "========================================" . PHP_EOL;
echo "CORRIGIENDO CANTIDADES DE INGRESOS" . PHP_EOL;
echo "========================================" . PHP_EOL . PHP_EOL;

// Primero, resetear todas las cantidades a 0
echo "1. Reseteando cantidades de productos..." . PHP_EOL;
Productos::query()->update(['cantidad' => 0]);
echo "   ✓ Productos reseteados" . PHP_EOL . PHP_EOL;

echo "2. Reseteando cantidades de inventarios..." . PHP_EOL;
Inventarios::query()->update(['cantidadProductos' => 0]);
echo "   ✓ Inventarios reseteados" . PHP_EOL . PHP_EOL;

// Ahora, recalcular basándose en los ingresos
echo "3. Recalculando cantidades desde ingresos..." . PHP_EOL;
$ingresos = Ingresos::all();

foreach ($ingresos as $ingreso) {
    echo "   Procesando Ingreso ID {$ingreso->id}: {$ingreso->cantidad} unidades" . PHP_EOL;
    
    // Actualizar producto
    $producto = Productos::find($ingreso->idProducto);
    if ($producto) {
        $producto->cantidad += $ingreso->cantidad;
        $producto->save();
        echo "     ✓ Producto '{$producto->nombre}': ahora tiene {$producto->cantidad} unidades" . PHP_EOL;
    }
    
    // Actualizar inventario
    $inventario = Inventarios::where('codigo', $ingreso->codigoInventario)->first();
    if ($inventario) {
        $inventario->cantidadProductos += $ingreso->cantidad;
        $inventario->save();
        echo "     ✓ Inventario '{$inventario->codigo}': ahora tiene {$inventario->cantidadProductos} unidades" . PHP_EOL;
    }
    echo PHP_EOL;
}

echo "========================================" . PHP_EOL;
echo "✓ CORRECCIÓN COMPLETADA" . PHP_EOL;
echo "========================================" . PHP_EOL;
