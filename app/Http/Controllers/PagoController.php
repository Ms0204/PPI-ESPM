<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');
        
        $pagos = Pagos::with('usuario')
            ->when($search, function($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('numeroPago', 'like', "%{$search}%")
                      ->orWhere('metodoPago', 'like', "%{$search}%")
                      ->orWhere('cantidad', 'like', "%{$search}%")
                      ->orWhere('fechaPago', 'like', "%{$search}%")
                      ->orWhere('cedulaUsuario', 'like', "%{$search}%")
                      ->orWhereHas('usuario', function($q) use ($search) {
                          $q->where('nombres', 'like', "%{$search}%")
                            ->orWhere('apellidos', 'like', "%{$search}%");
                      });
            })
            ->orderBy('fechaPago', 'desc')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'search' => $search]);
            
        $usuarios = Usuarios::where('activo', true)->get();
        return view('pagos.index', compact('pagos', 'usuarios'));
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
            "numeroPago" => "required",
            "metodoPago" => "required|in:efectivo,transferencia,cheque",
            "cantidad" => "required|numeric",
            "fechaPago" => "required|date",
            "cedulaUsuario" => "required|digits:10",
            "observaciones" => "nullable|string|max:1000"
        ]);

        // Don't accept an ID from the user — the DB will auto-generate it.
        $pago = Pagos::create($request->only(['numeroPago','metodoPago','cantidad','fechaPago','cedulaUsuario','observaciones']));

        return redirect()->route('pagos.index')->with('success', 'Pago creado correctamente.');
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
        $pago = Pagos::findOrFail($id);
        $request->validate([
            "numeroPago" => "required",
            "metodoPago" => "required|in:efectivo,transferencia,cheque",
            "cantidad" => "required|numeric",
            "fechaPago" => "required|date",
            "cedulaUsuario" => "required|digits:10",
            "observaciones" => "nullable|string|max:1000"
        ]);

        // Do NOT allow changing the primary key `id`.
        $pago->update($request->only(['numeroPago','metodoPago','cantidad','fechaPago','cedulaUsuario','observaciones']));

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pago = Pagos::findOrFail($id);
        $pago->delete();
        return redirect()->route('pagos.index')->with('success', 'Pago eliminado correctamente.');
    }

    /**
     * Generar reporte PDF de pagos.
     */
    public function generarPDF()
    {
        $pagos = Pagos::with('usuario')->orderBy('fechaPago','desc')->get();
        $pdf = Pdf::loadView('pagos.pdf', compact('pagos'));
        return $pdf->download('reporte_pagos_'.date('Y-m-d').'.pdf');
    }
}
