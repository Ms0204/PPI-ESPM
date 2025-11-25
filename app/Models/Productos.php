<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table = 'productos';
    protected $fillable = [
        'nombre',
        'cantidad',
        'idCategoria'
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
}
