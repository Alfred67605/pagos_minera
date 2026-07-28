<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anticipo extends Model
{
    protected $fillable = [
        'tipo_receptor',
        'trabajador_id',
        'socio_id',
        'fecha',
        'monto',
        'saldo',
        'pagado',
        'motivo',
        'caja_id',
        'user_id',
    ];

    protected $casts = [
        'pagado' => 'boolean',
        'fecha' => 'date',
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pagos()
    {
        return $this->belongsToMany(Pago::class, 'pago_anticipo')
                    ->withPivot('monto_descontado')
                    ->withTimestamps();
    }

    public function getMontoLetrasAttribute()
    {
        return Pago::convertirNumeroALetras($this->monto);
    }
}
