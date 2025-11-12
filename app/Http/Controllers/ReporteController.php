<?php

namespace App\Http\Controllers;

use App\Models\Reportes;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $reportes = Reportes::orderBy('created_at','desc')->paginate($perPage)->appends(['per_page' => $perPage]);
            return view('reportes.index', compact('reportes'));
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
            'tituloReporte' => 'required',
            'descripcion' => 'required',
            'fechaEmision' => 'required|date',
        ]);

        // No se acepta ID desde el usuario; la BD generará el id automaticamente.
        Reportes::create($request->only(['tituloReporte', 'descripcion', 'fechaEmision']));
        return redirect()->route('reportes.index')->with('success', 'Reporte creado correctamente.');
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
        $reporte = Reportes::findOrFail($id);
        $request->validate([
            'tituloReporte' => 'required',
            'descripcion' => 'required',
            'fechaEmision' => 'required|date',
        ]);

        // No permitir cambiar la PK id desde la UI. Actualizar sólo los campos permitidos.
        $reporte->update($request->only(['tituloReporte', 'descripcion', 'fechaEmision']));

        return redirect()->route('reportes.index')->with('success', 'Reporte actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reporte = Reportes::findOrFail($id);
        $reporte->delete();
        return redirect()->route('reportes.index')->with('success', 'Reporte eliminado correctamente.');
    }

    /**
     * Generar reporte PDF de reportes.
     */
    public function generarPDF()
    {
        $reportes = Reportes::orderBy('created_at','desc')->get();
        $pdf = Pdf::loadView('reportes.pdf', compact('reportes'));
        return $pdf->download('reporte_reportes_'.date('Y-m-d').'.pdf');
    }
}
