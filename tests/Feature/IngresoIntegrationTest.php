<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Productos;
use App\Models\Categorias;
use App\Models\Ingresos;
use App\Models\Inventarios;
use App\Models\Usuarios;

class IngresoIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test de integración: Crear un ingreso actualiza la cantidad del producto
     */
    public function test_crear_ingreso_actualiza_cantidad_producto(): void
    {
        // Crear usuario
        $usuario = Usuarios::create([
            'cedula' => '1234567890',
            'nombres' => 'Test',
            'apellidos' => 'Usuario',
            'correo' => 'test@test.com',
            'contrasena' => bcrypt('password'),
            'direccion' => 'Test',
            'telefono' => '0999999999',
            'activo' => true
        ]);

        // Crear categoría
        $categoria = Categorias::create([
            'nombre' => 'Electrónica',
            'descripcion' => 'Productos electrónicos',
            'estado' => 'Activo'
        ]);

        // Crear producto con cantidad inicial
        $producto = Productos::create([
            'nombre' => 'Laptop Dell',
            'cantidad' => 10,
            'idCategoria' => $categoria->id,
            'estado' => 'Activo'
        ]);

        // Crear inventario
        $inventario = Inventarios::create([
            'codigo' => 'INV-001',
            'tipoMovimiento' => 'entrada',
            'fechaRegistro' => now()->toDateString(),
            'cantidadProductos' => 10,
            'cedulaUsuario' => '1234567890'
        ]);

        // Guardar cantidad inicial
        $cantidadInicial = $producto->cantidad;

        // Crear ingreso vía HTTP para activar lógica de controlador
        $response = $this->post('/ingresos', [
            'idProducto' => $producto->id,
            'codigoInventario' => $inventario->codigo,
            'cantidad' => 5,
            'fechaIngreso' => now()->toDateString(),
            'observacion' => 'Ingreso de prueba'
        ]);

        $response->assertRedirect('/ingresos');

        // Cargar nuevamente producto e inventario
        $producto->refresh();
        $inventario->refresh();

        // Verificar actualización de cantidades
        $this->assertEquals($cantidadInicial + 5, $producto->cantidad);
        $this->assertDatabaseHas('ingresos', [
            'idProducto' => $producto->id,
            'cantidad' => 5
        ]);
    }

    /**
     * Test de integración: Editar un ingreso actualiza correctamente las cantidades
     */
    public function test_editar_ingreso_actualiza_cantidad_producto(): void
    {
        // Crear usuario
        Usuarios::create([
            'cedula' => '1234567890',
            'nombres' => 'Test',
            'apellidos' => 'Usuario',
            'correo' => 'test@test.com',
            'contrasena' => bcrypt('password'),
            'direccion' => 'Test',
            'telefono' => '0999999999',
            'activo' => true
        ]);

        // Crear datos iniciales
        $categoria = Categorias::create([
            'nombre' => 'Muebles',
            'descripcion' => 'Muebles de oficina',
            'estado' => 'Activo'
        ]);

        $producto = Productos::create([
            'nombre' => 'Escritorio',
            'cantidad' => 20,
            'idCategoria' => $categoria->id,
            'estado' => 'Activo'
        ]);

        $inventario = Inventarios::create([
            'codigo' => 'INV-002',
            'tipoMovimiento' => 'entrada',
            'fechaRegistro' => now()->toDateString(),
            'cantidadProductos' => 20,
            'cedulaUsuario' => '1234567890'
        ]);

        // Crear ingreso inicial de 10 unidades vía HTTP
        $this->post('/ingresos', [
            'idProducto' => $producto->id,
            'codigoInventario' => $inventario->codigo,
            'cantidad' => 10,
            'fechaIngreso' => now()->toDateString(),
            'observacion' => 'Ingreso inicial'
        ])->assertRedirect('/ingresos');

        // Obtener el ingreso recién creado
        $ingreso = Ingresos::where('idProducto', $producto->id)->latest()->first();
        $this->assertNotNull($ingreso);

        // Editar ingreso (cambiar cantidad a 15) vía HTTP
        $this->put("/ingresos/{$ingreso->id}", [
            'idProducto' => $producto->id,
            'codigoInventario' => $inventario->codigo,
            'cantidad' => 15,
            'fechaIngreso' => now()->toDateString(),
            'observacion' => 'Ingreso actualizado'
        ])->assertRedirect('/ingresos');

        // Verificar que la cantidad final sea correcta: 20 inicial + 15 nuevo = 35
        $producto->refresh();
        $this->assertEquals(35, $producto->cantidad);
    }

    /**
     * Test de integración: Eliminar un ingreso revierte la cantidad del producto
     */
    public function test_eliminar_ingreso_revierte_cantidad_producto(): void
    {
        // Crear usuario
        Usuarios::create([
            'cedula' => '1234567890',
            'nombres' => 'Test',
            'apellidos' => 'Usuario',
            'correo' => 'test@test.com',
            'contrasena' => bcrypt('password'),
            'direccion' => 'Test',
            'telefono' => '0999999999',
            'activo' => true
        ]);

        // Crear datos iniciales
        $categoria = Categorias::create([
            'nombre' => 'Papelería',
            'descripcion' => 'Artículos de oficina',
            'estado' => 'Activo'
        ]);

        $producto = Productos::create([
            'nombre' => 'Hojas A4',
            'cantidad' => 100,
            'idCategoria' => $categoria->id,
            'estado' => 'Activo'
        ]);

        $inventario = Inventarios::create([
            'codigo' => 'INV-003',
            'tipoMovimiento' => 'entrada',
            'fechaRegistro' => now()->toDateString(),
            'cantidadProductos' => 100,
            'cedulaUsuario' => '1234567890'
        ]);

        // Crear ingreso vía HTTP
        $this->post('/ingresos', [
            'idProducto' => $producto->id,
            'codigoInventario' => $inventario->codigo,
            'cantidad' => 50,
            'fechaIngreso' => now()->toDateString(),
            'observacion' => 'Ingreso temporal'
        ])->assertRedirect('/ingresos');

        $producto->refresh();
        $this->assertEquals(150, $producto->cantidad);

        // Obtener el ingreso creado y eliminar vía HTTP
        $ingreso = Ingresos::where('idProducto', $producto->id)->latest()->first();
        $this->delete("/ingresos/{$ingreso->id}")->assertRedirect('/ingresos');

        $producto->refresh();
        // Verificar que la cantidad volvió a 100
        $this->assertEquals(100, $producto->cantidad);
        $this->assertDatabaseMissing('ingresos', [ 'id' => $ingreso->id ]);
    }
}
