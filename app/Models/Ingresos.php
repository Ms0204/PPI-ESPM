<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingresos extends Model
{
    protected $table = 'ingresos';
    protected $fillable = [
        'cantidad',
        'fechaIngreso',
        'idProducto',
        'codigoInventario'
    ];

    public $timestamps = true;
    
    protected $casts = [
        'fechaIngreso' => 'date',
        'cantidad' => 'integer'
    ];

    /**
     * Relación con Producto
     */
    public function producto()
    {
        return $this->belongsTo(Productos::class, 'idProducto', 'id');
    }

    /**
     * Relación con Inventario
     */
    public function inventario()
    {
        return $this->belongsTo(Inventarios::class, 'codigoInventario', 'codigo');
    }
}
