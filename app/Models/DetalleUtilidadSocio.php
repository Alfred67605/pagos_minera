<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleUtilidadSocio extends Model
{
    use HasFactory;

    protected $table = 'detalle_utilidad_socios';

    protected $fillable = [
        'distribucion_utilidad_id',
        'socio_id',
        'porcentaje_participacion',
        'monto_utilidad',
        'estado',
    ];

    public function distribucion()
    {
        return $this->belongsTo(DistribucionUtilidad::class, 'distribucion_utilidad_id');
    }

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }
}
