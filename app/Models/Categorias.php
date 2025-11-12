<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    protected $table = 'categorias';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'idProducto'
    ];

    public $timestamps = true;

    /**
     * Relación con Producto
     */
    public function producto()
    {
        return $this->belongsTo(Productos::class, 'idProducto', 'id');
    }
}
