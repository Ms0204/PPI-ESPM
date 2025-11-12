<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reportes extends Model
{
    protected $table = 'reportes';
    protected $fillable = [
        'tituloReporte',
        'descripcion',
        'fechaEmision'
    ];

    public $timestamps = true;
}
