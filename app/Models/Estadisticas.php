<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Estadisticas extends Model
{
    use HasFactory;

    protected $table = 'estadisticas';

    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'id_parque',
        'tipo',
        'fecha',
        'cantidad',
    ];

    protected $cast = [
        'fecha' => 'date:d/M/Y',
    ];

    public $timestamps = false;

    public function tipovisitante()
    {
        return $this->belongsTo(TipoVisitante::class, 'tipo', 'id');
    }

    public function parque()
    {
        return $this->belongsTo(Parques::class, 'id_parque', 'id');
    }
}
