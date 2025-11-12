<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventarios extends Model
{
    use HasFactory;

    protected $table = 'inventarios';
    
    protected $fillable = [
        'codigo',
        'tipoMovimiento',
        'fechaRegistro',
        'cantidadProductos',
        'cedulaUsuario'
    ];

    protected $casts = [
        'fechaRegistro' => 'date',
        'cantidadProductos' => 'integer'
    ];

    /**
     * Obtiene el usuario asociado al inventario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'cedulaUsuario', 'cedula');
    }
}
