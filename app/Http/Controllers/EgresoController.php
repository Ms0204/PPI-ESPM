<?php

namespace App\Http\Controllers;

use App\Models\Egresos;
use App\Models\Productos;
use App\Models\Inventarios;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EgresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');
        
        $egresos = Egresos::with(['producto', 'inventario'])
            ->when($search, function($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('cantidad', 'like', "%{$search}%")
                      ->orWhere('fechaEgreso', 'like', "%{$search}%")
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
        return view('egresos.index', compact('egresos','productos','inventarios'));
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
            'fechaEgreso' => 'required|date',
            'idProducto' => 'required',
            'codigoInventario' => 'required',
            'observacion' => 'nullable|string',
        ]);

        // Crear el egreso
        $egreso = Egresos::create($request->only(['cantidad','fechaEgreso','idProducto','codigoInventario','observacion']));
        
        // Actualizar la cantidad en productos (restar)
        $producto = Productos::find($request->idProducto);
        if ($producto) {
            $producto->cantidad -= $request->cantidad;
            $producto->save();
        }
        
        // Actualizar la cantidad en inventarios (restar)
        $inventario = Inventarios::where('codigo', $request->codigoInventario)->first();
        if ($inventario) {
            $inventario->cantidadProductos -= $request->cantidad;
            $inventario->save();
        }
        
        return redirect()->route('egresos.index')->with('success', 'Egreso creado correctamente.');
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
        $egreso = Egresos::findOrFail($id);
        $request->validate([
            'cantidad' => 'required',
            'fechaEgreso' => 'required|date',
            'idProducto' => 'required',
            'codigoInventario' => 'required',
            'observacion' => 'nullable|string',
        ]);

        // Calcular la diferencia de cantidad
        $cantidadAnterior = $egreso->cantidad;
        $cantidadNueva = $request->cantidad;
        $diferencia = $cantidadNueva - $cantidadAnterior;
        
        // Si cambió el producto, sumar al producto anterior y restar del nuevo
        if ($egreso->idProducto != $request->idProducto) {
            // Sumar al producto anterior (devolver lo que se había restado)
            $productoAnterior = Productos::find($egreso->idProducto);
            if ($productoAnterior) {
                $productoAnterior->cantidad += $cantidadAnterior;
                $productoAnterior->save();
            }
            
            // Restar del nuevo producto
            $productoNuevo = Productos::find($request->idProducto);
            if ($productoNuevo) {
                $productoNuevo->cantidad -= $cantidadNueva;
                $productoNuevo->save();
            }
        } else {
            // Si es el mismo producto, ajustar con la diferencia (restar más o devolver)
            $producto = Productos::find($request->idProducto);
            if ($producto) {
                $producto->cantidad -= $diferencia;
                $producto->save();
            }
        }
        
        // Si cambió el inventario, sumar al inventario anterior y restar del nuevo
        if ($egreso->codigoInventario != $request->codigoInventario) {
            // Sumar al inventario anterior (devolver lo que se había restado)
            $inventarioAnterior = Inventarios::where('codigo', $egreso->codigoInventario)->first();
            if ($inventarioAnterior) {
                $inventarioAnterior->cantidadProductos += $cantidadAnterior;
                $inventarioAnterior->save();
            }
            
            // Restar del nuevo inventario
            $inventarioNuevo = Inventarios::where('codigo', $request->codigoInventario)->first();
            if ($inventarioNuevo) {
                $inventarioNuevo->cantidadProductos -= $cantidadNueva;
                $inventarioNuevo->save();
            }
        } else {
            // Si es el mismo inventario, ajustar con la diferencia (restar más o devolver)
            $inventario = Inventarios::where('codigo', $request->codigoInventario)->first();
            if ($inventario) {
                $inventario->cantidadProductos -= $diferencia;
                $inventario->save();
            }
        }

        // Actualizar el egreso
        $egreso->update($request->only(['cantidad','fechaEgreso','idProducto','codigoInventario','observacion']));

        return redirect()->route('egresos.index')->with('success', 'Egreso actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $egreso = Egresos::findOrFail($id);
        
        // Devolver la cantidad al producto (sumar porque se está eliminando el egreso)
        $producto = Productos::find($egreso->idProducto);
        if ($producto) {
            $producto->cantidad += $egreso->cantidad;
            $producto->save();
        }
        
        // Devolver la cantidad al inventario (sumar porque se está eliminando el egreso)
        $inventario = Inventarios::where('codigo', $egreso->codigoInventario)->first();
        if ($inventario) {
            $inventario->cantidadProductos += $egreso->cantidad;
            $inventario->save();
        }
        
        $egreso->delete();
        return redirect()->route('egresos.index')->with('success', 'Egreso eliminado correctamente.');
    }

    /**
     * Generar reporte PDF de egresos.
     */
    public function generarPDF()
    {
        $egresos = Egresos::with(['producto', 'inventario'])->orderBy('created_at','desc')->get();
        $pdf = Pdf::loadView('egresos.pdf', compact('egresos'));
        return $pdf->download('reporte_egresos_'.date('Y-m-d').'.pdf');
    }
}
