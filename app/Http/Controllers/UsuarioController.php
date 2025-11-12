<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $usuarios = Usuarios::orderBy('created_at','desc')->paginate($perPage)->appends(['per_page' => $perPage]);        
        return view('usuarios.index', compact('usuarios'));
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
            'cedula' => 'required|size:10|unique:usuarios,cedula',
            'usuario' => 'nullable|unique:usuarios,usuario',
            'contrasenia' => 'nullable',
            'nombres' => 'required',
            'apellidos' => 'required',
            'correo' => 'required|email|unique:usuarios,correo',
            'direccion' => 'required',
            'telefono' => 'required|max:15',
        ]);

        Usuarios::create($request->all());
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
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
        $usuario = Usuarios::findOrFail($id);

        $request->validate([
            'cedula' => 'required|size:10|unique:usuarios,cedula,' . $usuario->id,
            'usuario' => 'nullable|unique:usuarios,usuario,' . $usuario->id,
            'contrasenia' => 'nullable',
            'nombres' => 'required',
            'apellidos' => 'required',
            'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id,
            'direccion' => 'required',
            'telefono' => 'required|max:15',
            'activo' => 'boolean'
        ]);

        $usuario->update($request->all());
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = Usuarios::findOrFail($id);
        $usuario->activo = !$usuario->activo;
        $usuario->save();

        $mensaje = $usuario->activo ? 'activado' : 'desactivado';
        return redirect()->route('usuarios.index')->with('success', "Usuario {$mensaje} correctamente.");
    }

    /**
     * Generar reporte PDF de usuarios.
     */
    public function generarPDF()
    {
        $usuarios = Usuarios::orderBy('created_at','desc')->get();
        $pdf = Pdf::loadView('usuarios.pdf', compact('usuarios'));
        return $pdf->download('reporte_usuarios_'.date('Y-m-d').'.pdf');
    }
}
