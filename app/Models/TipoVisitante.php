<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVisitante extends Model
{
    use HasFactory;

    protected $table = 'tipovisitante';

    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [        
        'tipo',        
    ];

    public $timestamps = false;
    
    public function estadisticas()
    {
        return $this->hasMany(Estadisticas::class, 'tipo', 'id');
    }
}
