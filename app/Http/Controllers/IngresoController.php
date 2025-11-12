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
        $ingresos = Ingresos::orderBy('created_at','desc')->paginate($perPage)->appends(['per_page' => $perPage]);
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
        ]);

        // Do not accept id from the user; DB will auto-generate primary key.
        Ingresos::create($request->only(['cantidad','fechaIngreso','idProducto','codigoInventario']));
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
        ]);

        // Do not allow changing the PK id. Update only allowed fields.
        $ingreso->update($request->only(['cantidad','fechaIngreso','idProducto','codigoInventario']));

        return redirect()->route('ingresos.index')->with('success', 'Ingreso actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ingreso = Ingresos::findOrFail($id);
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
