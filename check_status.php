<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Egresos;
use App\Models\Ingresos;
use App\Models\Productos;
use App\Models\Inventarios;

echo "=== RESUMEN COMPLETO ===" . PHP_EOL . PHP_EOL;

echo "INGRESOS:" . PHP_EOL;
$ingresos = Ingresos::all();
foreach ($ingresos as $ing) {
    echo "  ID {$ing->id}: {$ing->cantidad} unidades - Producto {$ing->idProducto} - Inventario {$ing->codigoInventario}" . PHP_EOL;
}
echo "  Total ingresos: " . $ingresos->sum('cantidad') . " unidades" . PHP_EOL . PHP_EOL;

echo "EGRESOS:" . PHP_EOL;
$egresos = Egresos::all();
foreach ($egresos as $eg) {
    echo "  ID {$eg->id}: {$eg->cantidad} unidades - Producto {$eg->idProducto} - Inventario {$eg->codigoInventario}" . PHP_EOL;
}
echo "  Total egresos: " . $egresos->sum('cantidad') . " unidades" . PHP_EOL . PHP_EOL;

echo "PRODUCTOS:" . PHP_EOL;
$productos = Productos::all();
foreach ($productos as $prod) {
    echo "  ID {$prod->id} ({$prod->nombre}): {$prod->cantidad} unidades" . PHP_EOL;
}
echo PHP_EOL;

echo "INVENTARIOS:" . PHP_EOL;
$inventarios = Inventarios::all();
foreach ($inventarios as $inv) {
    echo "  {$inv->codigo}: {$inv->cantidadProductos} unidades" . PHP_EOL;
}

echo PHP_EOL . "=== CÁLCULO ESPERADO PARA PRODUCTO 1 ===" . PHP_EOL;
$ingresosP1 = Ingresos::where('idProducto', 1)->sum('cantidad');
$egresosP1 = Egresos::where('idProducto', 1)->sum('cantidad');
echo "Ingresos: {$ingresosP1}" . PHP_EOL;
echo "Egresos: {$egresosP1}" . PHP_EOL;
echo "Esperado: " . ($ingresosP1 - $egresosP1) . PHP_EOL;
echo "Real: " . Productos::find(1)->cantidad . PHP_EOL;
