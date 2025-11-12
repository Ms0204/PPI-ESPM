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
        $egresos = Egresos::orderBy('created_at','desc')->paginate($perPage)->appends(['per_page' => $perPage]);
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
        ]);

        // Do not accept id from the user; DB will auto-generate primary key.
        Egresos::create($request->only(['cantidad','fechaEgreso','idProducto','codigoInventario']));
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
        ]);

        // Do not allow changing the PK id. Update only allowed fields.
        $egreso->update($request->only(['cantidad','fechaEgreso','idProducto','codigoInventario']));

        return redirect()->route('egresos.index')->with('success', 'Egreso actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $egreso = Egresos::findOrFail($id);
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
