<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribucionUtilidad extends Model
{
    use HasFactory;

    protected $table = 'distribucion_utilidades';

    protected $fillable = [
        'numero_distribucion',
        'periodo',
        'fecha',
        'utilidad_bruta_total',
        'deducciones_reserva',
        'utilidad_neta_distribuir',
        'observaciones',
        'user_id',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleUtilidadSocio::class, 'distribucion_utilidad_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
