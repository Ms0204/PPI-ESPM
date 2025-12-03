<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Productos;
use App\Models\Categorias;
use App\Models\Usuarios;
use App\Models\Roles;
use App\Models\Permisos;

class ProductoE2ETest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test E2E: Flujo completo de creación de producto desde request HTTP
     */
    public function test_crear_producto_flujo_completo(): void
    {
        // Crear categoría necesaria
        $categoria = Categorias::create([
            'nombre' => 'Tecnología',
            'descripcion' => 'Productos tecnológicos',
            'estado' => 'Activo'
        ]);

        // Datos del producto
        $productoData = [
            'nombre' => 'Mouse Inalámbrico',
            'cantidad' => 25,
            'idCategoria' => $categoria->id
        ];

        // Simular request POST para crear producto
        $response = $this->post('/productos', $productoData);

        // Verificar redirección exitosa
        $response->assertRedirect('/productos');
        $response->assertSessionHas('success');

        // Verificar que el producto fue creado en la base de datos
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Mouse Inalámbrico',
            'cantidad' => 25,
            'idCategoria' => $categoria->id,
            'estado' => 'Activo'
        ]);

        // Verificar que se puede obtener el producto creado
        $producto = Productos::where('nombre', 'Mouse Inalámbrico')->first();
        $this->assertNotNull($producto);
        $this->assertEquals(25, $producto->cantidad);
        $this->assertEquals('Activo', $producto->estado);
    }

    /**
     * Test E2E: Flujo completo de edición de producto
     */
    public function test_editar_producto_flujo_completo(): void
    {
        // Crear categoría y producto
        $categoria = Categorias::create([
            'nombre' => 'Accesorios',
            'descripcion' => 'Accesorios varios',
            'estado' => 'Activo'
        ]);

        $producto = Productos::create([
            'nombre' => 'Teclado Mecánico',
            'cantidad' => 15,
            'idCategoria' => $categoria->id,
            'estado' => 'Activo'
        ]);

        // Datos actualizados
        $datosActualizados = [
            'nombre' => 'Teclado Mecánico RGB',
            'cantidad' => 20,
            'idCategoria' => $categoria->id
        ];

        // Simular request PUT para actualizar
        $response = $this->put("/productos/{$producto->id}", $datosActualizados);

        // Verificar redirección
        $response->assertRedirect('/productos');

        // Verificar actualización en base de datos
        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Teclado Mecánico RGB',
            'cantidad' => 20
        ]);

        // Verificar que los datos antiguos no existen
        $this->assertDatabaseMissing('productos', [
            'id' => $producto->id,
            'nombre' => 'Teclado Mecánico',
            'cantidad' => 15
        ]);
    }

    /**
     * Test E2E: Flujo completo de visualización de listado de productos
     */
    public function test_visualizar_listado_productos_flujo_completo(): void
    {
        // Crear múltiples productos
        $categoria = Categorias::create([
            'nombre' => 'Hardware',
            'descripcion' => 'Componentes de hardware',
            'estado' => 'Activo'
        ]);

        Productos::create([
            'nombre' => 'RAM 8GB',
            'cantidad' => 30,
            'idCategoria' => $categoria->id,
            'estado' => 'Activo'
        ]);

        Productos::create([
            'nombre' => 'SSD 256GB',
            'cantidad' => 20,
            'idCategoria' => $categoria->id,
            'estado' => 'Activo'
        ]);

        Productos::create([
            'nombre' => 'Disco Duro 1TB',
            'cantidad' => 15,
            'idCategoria' => $categoria->id,
            'estado' => 'Inactivo'
        ]);

        // Simular request GET al índice
        $response = $this->get('/productos');

        // Verificar respuesta exitosa
        $response->assertStatus(200);

        // Verificar que contiene los nombres de productos
        $response->assertSee('RAM 8GB');
        $response->assertSee('SSD 256GB');
        $response->assertSee('Disco Duro 1TB');

        // Verificar que los productos activos aparecen primero (por ordenamiento)
        $content = $response->getContent();
        $posicionRAM = strpos($content, 'RAM 8GB');
        $posicionDisco = strpos($content, 'Disco Duro 1TB');
        
        // Los productos activos deben aparecer antes que los inactivos
        $this->assertLessThan($posicionDisco, $posicionRAM);
    }

    /**
     * Test E2E: Validación de campos requeridos al crear producto
     */
    public function test_validacion_campos_requeridos_producto(): void
    {
        // Intentar crear producto sin nombre (campo requerido)
        $datosInvalidos = [
            'nombre' => '',
            'cantidad' => 10,
            'idCategoria' => 1
        ];

        $response = $this->post('/productos', $datosInvalidos);

        // Verificar que hay errores de validación
        $response->assertSessionHasErrors(['nombre']);

        // Verificar que el producto no fue creado
        $this->assertDatabaseMissing('productos', [
            'cantidad' => 10
        ]);
    }
}
