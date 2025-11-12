<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table = 'productos';
    protected $fillable = [
        'nombre',
        'cantidad'
    ];

    public $timestamps = true;

    protected $casts = [
        'cantidad' => 'integer'
    ];
}
