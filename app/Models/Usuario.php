<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuario';

    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [        
        'nombre',
        'usuario',
        'contrasenia',
        'dui',
        'tipo',
        'activo',
    ];

    protected $cast = [
        'fecha' => 'date:d/M/Y',
    ];

    public $timestamps = false;

    public function usuario_parques()
    {
        return $this->belongsToMany(Parques::class, 'usuario_parque', 'id_usuario', 'id_parque');
    }
}
