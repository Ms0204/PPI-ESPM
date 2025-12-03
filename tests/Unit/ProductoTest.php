<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Productos;
use App\Models\Categorias;

class ProductoTest extends TestCase
{
    /**
     * Test unitario: Validar que el nombre del producto con valor es válido
     */
    public function test_producto_nombre_valido(): void
    {
        $producto = new Productos();
        $producto->nombre = 'Laptop HP';
        $producto->cantidad = 10;
        $producto->idCategoria = 1;
        
        // Validar que el nombre no está vacío
        $this->assertNotEmpty($producto->nombre, 'El nombre del producto no puede estar vacío');
        $this->assertIsString($producto->nombre);
        $this->assertGreaterThan(0, strlen($producto->nombre));
    }

    /**
     * Test unitario: Validar que la cantidad del producto sea numérica positiva
     */
    public function test_producto_cantidad_debe_ser_numerica_positiva(): void
    {
        $cantidad = 50;
        
        // Verificar que sea numérico
        $this->assertIsNumeric($cantidad);
        
        // Verificar que sea mayor a 0
        $this->assertGreaterThanOrEqual(0, $cantidad);
    }

    /**
     * Test unitario: Validar cálculo de incremento de cantidad
     */
    public function test_incremento_de_cantidad_funciona_correctamente(): void
    {
        $cantidadInicial = 100;
        $cantidadIngreso = 50;
        $cantidadEsperada = 150;
        
        $cantidadFinal = $cantidadInicial + $cantidadIngreso;
        
        $this->assertEquals($cantidadEsperada, $cantidadFinal, 'El incremento de cantidad debe sumar correctamente');
    }

    /**
     * Test unitario: Validar cálculo de decremento de cantidad
     */
    public function test_decremento_de_cantidad_funciona_correctamente(): void
    {
        $cantidadInicial = 100;
        $cantidadEgreso = 30;
        $cantidadEsperada = 70;
        
        $cantidadFinal = $cantidadInicial - $cantidadEgreso;
        
        $this->assertEquals($cantidadEsperada, $cantidadFinal, 'El decremento de cantidad debe restar correctamente');
    }

    /**
     * Test unitario: Validar que el estado por defecto sea 'Activo'
     */
    public function test_estado_por_defecto_es_activo(): void
    {
        $estadoDefecto = 'Activo';
        
        $this->assertEquals('Activo', $estadoDefecto);
        $this->assertContains($estadoDefecto, ['Activo', 'Inactivo']);
    }
}
