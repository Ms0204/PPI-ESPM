<?php

namespace App\Http\Controllers;

use App\Models\Permisos;
use App\Models\Usuarios;
use App\Models\Roles;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $permisos = Permisos::with(['usuario', 'rol'])
                // Activos primero (0), Inactivos después (1), cada grupo ordenado por fecha más reciente primero
                ->orderByRaw("CASE WHEN estado='Activo' THEN 0 ELSE 1 END")
                ->orderBy('fechaAsignacion', 'desc')
                    ->paginate($perPage)
                    ->appends(['per_page' => $perPage]);
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
            'cedulaUsuario' => 'required|exists:usuarios,cedula',
            'idRol' => 'required|exists:roles,id',
        ]);

        // Verificar si ya existe un permiso con la misma combinación de usuario y rol
        $existePermiso = Permisos::where('cedulaUsuario', $request->cedulaUsuario)
                                  ->where('idRol', $request->idRol)
                                  ->exists();
        
        if ($existePermiso) {
            return redirect()->route('permisos.index')
                           ->with('error', 'Ya existe un permiso con este usuario y rol.');
        }

        // Do not accept id from the user; DB will auto-generate primary key.
        // Set fechaAsignacion to current date and estado to 'Activo' automatically
        $data = $request->only(['cedulaUsuario','idRol']);
        // Usar timezone de Ecuador para la fecha de asignación
        $data['fechaAsignacion'] = Carbon::now('America/Guayaquil')->toDateString();
        $data['estado'] = 'Activo';
        
        Permisos::create($data);
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

        // Verificar si ya existe otro permiso con la misma combinación de usuario y rol
        $existePermiso = Permisos::where('cedulaUsuario', $request->cedulaUsuario)
                                  ->where('idRol', $request->idRol)
                                  ->where('id', '!=', $id)
                                  ->exists();
        
        if ($existePermiso) {
            return redirect()->route('permisos.index')
                           ->with('error', 'Ya existe un permiso con este usuario y rol.');
        }

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
        $permisos = Permisos::with(['usuario', 'rol'])
                ->orderByRaw("CASE WHEN estado='Activo' THEN 0 ELSE 1 END")
                ->orderBy('fechaAsignacion', 'desc')
                    ->get();
        $pdf = Pdf::loadView('permisos.pdf', compact('permisos'));
        return $pdf->download('reporte_permisos_'.date('Y-m-d').'.pdf');
    }
}
