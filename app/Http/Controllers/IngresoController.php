<?php

namespace App\Http\Controllers;

use App\Models\Ingresos;
use App\Models\Productos;
use App\Models\Inventarios;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');
        
        $ingresos = Ingresos::with(['producto', 'inventario'])
            ->when($search, function($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('cantidad', 'like', "%{$search}%")
                      ->orWhere('fechaIngreso', 'like', "%{$search}%")
                      ->orWhere('idProducto', 'like', "%{$search}%")
                      ->orWhere('codigoInventario', 'like', "%{$search}%")
                      ->orWhere('observacion', 'like', "%{$search}%")
                      ->orWhereHas('producto', function($q) use ($search) {
                          $q->where('nombre', 'like', "%{$search}%");
                      })
                      ->orWhereHas('inventario', function($q) use ($search) {
                          $q->where('codigo', 'like', "%{$search}%");
                      });
            })
            ->orderBy('created_at','desc')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'search' => $search]);
            
        $productos = Productos::all();
        $inventarios = Inventarios::all();
        return view('ingresos.index', compact('ingresos','productos','inventarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cantidad' => 'required',
            'fechaIngreso' => 'required|date',
            'idProducto' => 'required',
            'codigoInventario' => 'required',
            'observacion' => 'nullable|string',
        ]);

        // Crear el ingreso
        $ingreso = Ingresos::create($request->only(['cantidad','fechaIngreso','idProducto','codigoInventario','observacion']));
        
        // Actualizar la cantidad en productos (sumar)
        $producto = Productos::find($request->idProducto);
        if ($producto) {
            $producto->cantidad += $request->cantidad;
            $producto->save();
        }
        
        // Actualizar la cantidad en inventarios (sumar)
        $inventario = Inventarios::where('codigo', $request->codigoInventario)->first();
        if ($inventario) {
            $inventario->cantidadProductos += $request->cantidad;
            $inventario->save();
        }
        
        return redirect()->route('ingresos.index')->with('success', 'Ingreso creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ingreso = Ingresos::findOrFail($id);
        $request->validate([
            'cantidad' => 'required',
            'fechaIngreso' => 'required|date',
            'idProducto' => 'required',
            'codigoInventario' => 'required',
            'observacion' => 'nullable|string',
        ]);

        // Calcular la diferencia de cantidad
        $cantidadAnterior = $ingreso->cantidad;
        $cantidadNueva = $request->cantidad;
        $diferencia = $cantidadNueva - $cantidadAnterior;
        
        // Si cambió el producto, restar del producto anterior y sumar al nuevo
        if ($ingreso->idProducto != $request->idProducto) {
            // Restar del producto anterior
            $productoAnterior = Productos::find($ingreso->idProducto);
            if ($productoAnterior) {
                $productoAnterior->cantidad -= $cantidadAnterior;
                $productoAnterior->save();
            }
            
            // Sumar al nuevo producto
            $productoNuevo = Productos::find($request->idProducto);
            if ($productoNuevo) {
                $productoNuevo->cantidad += $cantidadNueva;
                $productoNuevo->save();
            }
        } else {
            // Si es el mismo producto, solo actualizar con la diferencia
            $producto = Productos::find($request->idProducto);
            if ($producto) {
                $producto->cantidad += $diferencia;
                $producto->save();
            }
        }
        
        // Si cambió el inventario, restar del inventario anterior y sumar al nuevo
        if ($ingreso->codigoInventario != $request->codigoInventario) {
            // Restar del inventario anterior
            $inventarioAnterior = Inventarios::where('codigo', $ingreso->codigoInventario)->first();
            if ($inventarioAnterior) {
                $inventarioAnterior->cantidadProductos -= $cantidadAnterior;
                $inventarioAnterior->save();
            }
            
            // Sumar al nuevo inventario
            $inventarioNuevo = Inventarios::where('codigo', $request->codigoInventario)->first();
            if ($inventarioNuevo) {
                $inventarioNuevo->cantidadProductos += $cantidadNueva;
                $inventarioNuevo->save();
            }
        } else {
            // Si es el mismo inventario, solo actualizar con la diferencia
            $inventario = Inventarios::where('codigo', $request->codigoInventario)->first();
            if ($inventario) {
                $inventario->cantidadProductos += $diferencia;
                $inventario->save();
            }
        }

        // Actualizar el ingreso
        $ingreso->update($request->only(['cantidad','fechaIngreso','idProducto','codigoInventario','observacion']));

        return redirect()->route('ingresos.index')->with('success', 'Ingreso actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ingreso = Ingresos::findOrFail($id);
        
        // Restar la cantidad del producto
        $producto = Productos::find($ingreso->idProducto);
        if ($producto) {
            $producto->cantidad -= $ingreso->cantidad;
            $producto->save();
        }
        
        // Restar la cantidad del inventario
        $inventario = Inventarios::where('codigo', $ingreso->codigoInventario)->first();
        if ($inventario) {
            $inventario->cantidadProductos -= $ingreso->cantidad;
            $inventario->save();
        }
        
        $ingreso->delete();
        return redirect()->route('ingresos.index')->with('success', 'Ingreso eliminado correctamente.');
    }

    /**
     * Generar reporte PDF de ingresos.
     */
    public function generarPDF()
    {
        $ingresos = Ingresos::with(['producto', 'inventario'])->orderBy('created_at','desc')->get();
        $pdf = Pdf::loadView('ingresos.pdf', compact('ingresos'));
        return $pdf->download('reporte_ingresos_'.date('Y-m-d').'.pdf');
    }
}
