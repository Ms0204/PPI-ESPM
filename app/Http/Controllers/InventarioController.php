<?php

namespace App\Http\Controllers;

use App\Models\Inventarios;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 5);
            $search = $request->get('search');
            
            $inventarios = Inventarios::with('usuario')
                ->when($search, function($query) use ($search) {
                    $query->where('codigo', 'like', "%{$search}%")
                          ->orWhere('tipoMovimiento', 'like', "%{$search}%")
                          ->orWhere('cantidadProductos', 'like', "%{$search}%")
                          ->orWhere('fechaRegistro', 'like', "%{$search}%")
                          ->orWhere('cedulaUsuario', 'like', "%{$search}%")
                          ->orWhereHas('usuario', function($q) use ($search) {
                              $q->where('nombres', 'like', "%{$search}%")
                                ->orWhere('apellidos', 'like', "%{$search}%");
                          });
                })
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->appends(['per_page' => $perPage, 'search' => $search]);
            
            $usuarios = Usuarios::where('activo', true)->get();
        } catch (\Exception $e) {
            $inventarios = collect([]); // Colección vacía para cuando hay error
            $usuarios = collect([]); // Colección vacía para usuarios
            session()->flash('error', 'Error al cargar los inventarios: ' . $e->getMessage());
        }

        return view("inventarios.index", compact("inventarios", "usuarios"));
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
            "codigo" => "required|unique:inventarios,codigo",
            "tipoMovimiento" => "required|in:entrada,salida",
            "fechaRegistro" => "required|date",
            "cantidadProductos" => "required|numeric",
            "cedulaUsuario" => "required|digits:10"
        ]);

        Inventarios::create($request->all());
        return redirect()->route('inventarios.index')->with('success', 'Inventario creado correctamente.');
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
        $inventario = Inventarios::findOrFail($id);

        $request->validate([
            "tipoMovimiento" => "required|in:entrada,salida",
            "fechaRegistro" => "required|date",
            "cantidadProductos" => "required|numeric",
            "cedulaUsuario" => "required|digits:10"
        ]);

        $inventario->update($request->except('codigo'));
        return redirect()->route('inventarios.index')->with('success', 'Inventario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inventario = Inventarios::findOrFail($id);
        $inventario->delete();
        return redirect()->route('inventarios.index')->with('success', 'Inventario eliminado correctamente.');
    }

    /**
     * Generar reporte PDF de inventarios.
     */
    public function generarPDF()
    {
        $inventarios = Inventarios::with('usuario')->orderBy('created_at','desc')->get();
        $pdf = Pdf::loadView('inventarios.pdf', compact('inventarios'));
        return $pdf->download('reporte_inventarios_'.date('Y-m-d').'.pdf');
    }
}
