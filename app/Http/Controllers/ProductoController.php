<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use App\Models\Categorias;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');
        
        $productos = Productos::with('categoria')
            ->when($search, function($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('nombre', 'like', "%{$search}%")
                      ->orWhere('cantidad', 'like', "%{$search}%")
                      ->orWhereHas('categoria', function($q) use ($search) {
                          $q->where('nombre', 'like', "%{$search}%");
                      });
            })
            ->orderByRaw("CASE WHEN estado='Activo' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'search' => $search]);
            
        $categorias = Categorias::all();
        return view('productos.index', compact('productos', 'categorias'));
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
            'nombre' => 'required',
            'cantidad' => 'required|integer|min:0',
            'idCategoria' => 'nullable|exists:categorias,id',
        ]);

        // Do not accept ID from user; DB will auto-increment
        Productos::create($request->only(['nombre','cantidad','idCategoria']));
        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
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
        $producto = Productos::findOrFail($id);
        $request->validate([
            'nombre' => 'required',
            'cantidad' => 'required|integer|min:0',
            'idCategoria' => 'nullable|exists:categorias,id',
        ]);

        // Do not allow changing the primary key `id`.
        $producto->update($request->only(['nombre','cantidad','idCategoria']));
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Productos::findOrFail($id);
        
        $tieneRelaciones = $producto->ingresos()->exists() || $producto->egresos()->exists();
        
        if ($tieneRelaciones) {
            $producto->estado = ($producto->estado === 'Activo') ? 'Inactivo' : 'Activo';
            $producto->save();
            $mensaje = $producto->estado === 'Activo' ? 'activado' : 'desactivado';
            return redirect()->route('productos.index')->with('success', "Producto {$mensaje} correctamente.");
        } else {
            $producto->delete();
            return redirect()->route('productos.index')->with('success', 'Producto eliminado permanentemente.');
        }
    }

    /**
     * Generar reporte PDF de productos.
     */
    public function generarPDF()
    {
        $productos = Productos::with('categoria')
                              ->orderByRaw("CASE WHEN estado='Activo' THEN 0 ELSE 1 END")
                              ->orderBy('created_at','desc')
                              ->get();
        $pdf = Pdf::loadView('productos.pdf', compact('productos'));
        return $pdf->download('reporte_productos_'.date('Y-m-d').'.pdf');
    }
}
