<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    use HasFactory;
    protected $table = 'usuarios';
    protected $fillable = ["cedula","usuario","contrasenia","nombres",
        "apellidos","correo","direccion","telefono","activo"];

    /**
     * Obtiene los inventarios asociados al usuario.
     */
    public function inventarios()
    {
        return $this->hasMany(Inventarios::class, 'cedulaUsuario', 'cedula');
    }
}
