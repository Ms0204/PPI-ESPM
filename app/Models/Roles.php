<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    public $timestamps = true;

    /**
     * Relación con permisos
     */
    public function permisos()
    {
        return $this->hasMany(Permisos::class, 'idRol', 'id');
    }
}
