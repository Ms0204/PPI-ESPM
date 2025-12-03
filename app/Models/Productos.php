<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table = 'productos';
    protected $fillable = [
        'nombre',
        'cantidad',
        'idCategoria',
        'estado'
    ];

    public $timestamps = true;

    protected $casts = [
        'cantidad' => 'integer'
    ];

    /**
     * Relación: Un producto pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categorias::class, 'idCategoria', 'id');
    }

    /**
     * Relación: Un producto tiene muchos ingresos
     */
    public function ingresos()
    {
        return $this->hasMany(Ingresos::class, 'idProducto', 'id');
    }

    /**
     * Relación: Un producto tiene muchos egresos
     */
    public function egresos()
    {
        return $this->hasMany(Egresos::class, 'idProducto', 'id');
    }
}
