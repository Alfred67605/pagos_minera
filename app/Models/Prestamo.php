<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    protected $fillable = [
        'numero_prestamo',
        'socio_id',
        'trabajador_id',
        'monto_total',
        'monto_cuota',
        'total_cuotas',
        'cuotas_pagadas',
        'saldo_pendiente',
        'fecha_otorgamiento',
        'estado',
        'observaciones',
        'user_id',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }

    public function cuotas()
    {
        return $this->hasMany(CuotaPrestamo::class, 'prestamo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
