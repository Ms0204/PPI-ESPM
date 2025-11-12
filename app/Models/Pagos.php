<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table = 'pagos';
    protected $fillable = [
        'numeroPago',
        'metodoPago',
        'cantidad',
        'fechaPago',
        'cedulaUsuario',
        'observaciones'
    ];

    public $timestamps = true;

    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'cedulaUsuario', 'cedula');
    }
}
