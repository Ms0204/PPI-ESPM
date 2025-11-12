<?php

namespace App\Http\Controllers;

use App\Models\Permisos;
use App\Models\Usuarios;
use App\Models\Roles;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $permisos = Permisos::orderBy('created_at','desc')->paginate($perPage)->appends(['per_page' => $perPage]);
        $usuarios = Usuarios::all();
        $roles = Roles::all();
        return view('permisos.index', compact('permisos','usuarios','roles'));
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
            'fechaAsignacion' => 'required|date',
            'estado' => 'required',
            'cedulaUsuario' => 'required|exists:usuarios,cedula',
            'idRol' => 'required|exists:roles,id',
        ]);

        // Do not accept id from the user; DB will auto-generate primary key.
        Permisos::create($request->only(['fechaAsignacion','estado','cedulaUsuario','idRol']));
        return redirect()->route('permisos.index')->with('success', 'Permiso creado correctamente.');
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
        $permiso = Permisos::findOrFail($id);
        $request->validate([
            'fechaAsignacion' => 'required|date',
            'estado' => 'required',
            'cedulaUsuario' => 'required|exists:usuarios,cedula',
            'idRol' => 'required|exists:roles,id',
        ]);

        // Do not allow changing the PK id. Update only allowed fields.
        $permiso->update($request->only(['fechaAsignacion','estado','cedulaUsuario','idRol']));

        return redirect()->route('permisos.index')->with('success', 'Permiso actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permiso = Permisos::findOrFail($id);
        // Alternar entre Activo e Inactivo
        $permiso->estado = ($permiso->estado == 'Activo') ? 'Inactivo' : 'Activo';
        $permiso->save();

        $mensaje = $permiso->estado == 'Activo' ? 'activado' : 'desactivado';
        return redirect()->route('permisos.index')->with('success', "Permiso {$mensaje} correctamente.");
    }

    /**
     * Generar reporte PDF de permisos.
     */
    public function generarPDF()
    {
        $permisos = Permisos::with(['usuario', 'rol'])->orderBy('created_at','desc')->get();
        $pdf = Pdf::loadView('permisos.pdf', compact('permisos'));
        return $pdf->download('reporte_permisos_'.date('Y-m-d').'.pdf');
    }
}
