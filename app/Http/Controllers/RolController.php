<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');
        
        $roles = Roles::when($search, function($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('nombre', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%");
            })
            ->orderBy('created_at','desc')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'search' => $search]);
            
        return view('roles.index', compact('roles'));
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
        Roles::create($request->only(['nombre','descripcion']));
        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
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
        $rol = Roles::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
        ]);

        // Do not allow changing the PK id. Update only allowed fields.
        $rol->update($request->only(['nombre','descripcion']));

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Roles::findOrFail($id);
        
        // Verificar si tiene permisos relacionados
        $tieneRelaciones = $rol->permisos()->exists();
        
        if ($tieneRelaciones) {
            // Eliminación lógica: alternar estado
            $rol->estado = ($rol->estado == 'Activo') ? 'Inactivo' : 'Activo';
            $rol->save();
            $mensaje = $rol->estado == 'Activo' ? 'activado' : 'desactivado';
            return redirect()->route('roles.index')->with('success', "Rol {$mensaje} correctamente.");
        } else {
            // Eliminación física
            $rol->delete();
            return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
        }
    }

    /**
     * Generar reporte PDF de roles.
     */
    public function generarPDF()
    {
        $roles = Roles::orderBy('created_at','desc')->get();
        $pdf = Pdf::loadView('roles.pdf', compact('roles'));
        return $pdf->download('reporte_roles_'.date('Y-m-d').'.pdf');
    }
}
