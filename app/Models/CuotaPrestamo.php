<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuotaPrestamo extends Model
{
    use HasFactory;

    protected $table = 'cuotas_prestamo';

    protected $fillable = [
        'prestamo_id',
        'numero_cuota',
        'monto_cuota',
        'fecha_vencimiento',
        'fecha_pago',
        'estado',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'prestamo_id');
    }
}
