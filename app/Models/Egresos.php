<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Egresos extends Model
{
    protected $table = 'egresos';
    
    protected $fillable = [
        'cantidad',
        'fechaEgreso',
        'idProducto',
        'codigoInventario'
    ];

    public $timestamps = true;
    
    protected $casts = [
        'fechaEgreso' => 'date',
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
