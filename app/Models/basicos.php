<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class basicos extends Model
{
    use HasFactory;
    protected $fillable = [        
        'nombre',
        'logo',           
        'direccion',
        'redSocial1',
        'redSocial2',
        'redSocial3',
        'telefono1',
        'telefono2',
        'idUsuarioCrea',
        'fechaCrea'
    ];
    public $timestamps = false;
}
