<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'fechaAsignacion',
        'estado',
        'cedulaUsuario',
        'idRol'
    ];

    public $timestamps = true;

    protected $casts = [
        // Cast as date so it renders without time by default (Y-m-d)
        'fechaAsignacion' => 'date'
    ];

    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'cedulaUsuario', 'cedula');
    }

    /**
     * Relación con Rol
     */
    public function rol()
    {
        return $this->belongsTo(Roles::class, 'idRol', 'id');
    }
}
