<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Productos;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');
        
        $categorias = Categorias::when($search, function($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('nombre', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%");
            })
            ->orderBy('created_at','desc')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'search' => $search]);
            
        return view('categorias.index', compact('categorias'));
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
            'descripcion' => 'required',
        ]);

        // Do not accept id from the user; DB will auto-generate primary key.
        Categorias::create($request->only(['nombre','descripcion']));
        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente.');
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
        $categoria = Categorias::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
        ]);

        // Do not allow changing the PK id. Update only allowed fields.
        $categoria->update($request->only(['nombre','descripcion']));

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = Categorias::findOrFail($id);
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente.');
    }

    /**
     * Generar reporte PDF de categorías.
     */
    public function generarPDF()
    {
        $categorias = Categorias::orderBy('created_at','desc')->get();
        $pdf = Pdf::loadView('categorias.pdf', compact('categorias'));
        return $pdf->download('reporte_categorias_'.date('Y-m-d').'.pdf');
    }
}
