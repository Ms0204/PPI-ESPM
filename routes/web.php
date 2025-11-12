<?php

use App\Http\Controllers\Auth\PasswordRecoveryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Ruta para mostrar el formulario de login
Route::get('/', function () {
    return view('index'); // resources/views/login.blade.php
})->name('login');

// Ruta para procesar el login
Route::post('/login', [AuthController::class, 'procesarLogin'])->name('login.procesar');

// Ruta para recuperar contraseña del login
Route::post('/recover-password', [PasswordRecoveryController::class, 'recover'])->name('password.recover');

// Ruta para la vista de home
Route::get('/home', function () {
    return view('home.home'); // resources/views/home.blade.php
})->name('home');

// Ruta para la vista de Usuarios
Route::resource('usuarios', UsuarioController::class);
Route::get('usuarios-pdf', [UsuarioController::class, 'generarPDF'])->name('usuarios.pdf');

// Ruta para la vista de Inventarios
Route::resource('inventarios', InventarioController::class);
Route::get('inventarios-pdf', [InventarioController::class, 'generarPDF'])->name('inventarios.pdf');

// Ruta para la vista de Pagos
Route::resource('pagos', PagoController::class);
Route::get('pagos-pdf', [PagoController::class, 'generarPDF'])->name('pagos.pdf');

// Ruta para la vista de Reportes
Route::resource('reportes', ReporteController::class);
Route::get('reportes-pdf', [ReporteController::class, 'generarPDF'])->name('reportes.pdf');

// Ruta para la vista de Ingresos
Route::resource('ingresos', IngresoController::class);
Route::get('ingresos-pdf', [IngresoController::class, 'generarPDF'])->name('ingresos.pdf');

// Ruta para la vista de Egresos
Route::resource('egresos', EgresoController::class);
Route::get('egresos-pdf', [EgresoController::class, 'generarPDF'])->name('egresos.pdf');

// Ruta para la vista de Productos
Route::resource('productos', ProductoController::class);
Route::get('productos-pdf', [ProductoController::class, 'generarPDF'])->name('productos.pdf');

// Ruta para la vista de Categorías
Route::resource('categorias', CategoriaController::class);
Route::get('categorias-pdf', [CategoriaController::class, 'generarPDF'])->name('categorias.pdf');

// Ruta para la vista de Roles
Route::resource('roles', RolController::class);
Route::get('roles-pdf', [RolController::class, 'generarPDF'])->name('roles.pdf');

// Ruta para la vista de Permisos
Route::resource('permisos', PermisoController::class);
Route::get('permisos-pdf', [PermisoController::class, 'generarPDF'])->name('permisos.pdf');