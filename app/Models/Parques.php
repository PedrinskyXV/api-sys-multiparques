<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parques extends Model
{
    use HasFactory;

    protected $table = 'parques';

    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [        
        'nombre',        
    ];

    public $timestamps = false;

    public function estadisticas()
    {
        return $this->hasMany(Estadisticas::class, 'id_parque', 'id');
    }

    public function parques_usuario()
    {
        return $this->belongsToMany(Usuario::class, 'usuario_parque', 'id_parque', 'id_usuario');
    }
}
