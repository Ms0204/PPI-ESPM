<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
        public function procesarLogin(Request $request)
    {
        $usuario = $request->input('usuario');
        $contraseña = $request->input('contraseña');

        // Validación básica (puedes reemplazar esto con autenticación real)
        if ($usuario === 'admin@gmail.com' && $contraseña === '12345') {
            return redirect()->route('home');
        } else {
            return back()->with('error', 'Usuario o contraseña incorrectos');
        }
    }

    public function showLoginForm()
    {
        return view('login.login');
    }
}
