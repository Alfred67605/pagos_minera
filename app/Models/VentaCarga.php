<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaCarga extends Model
{
    use HasFactory;

    protected $table = 'venta_cargas';

    protected $fillable = [
        'numero_venta',
        'fecha',
        'socio_id',
        'bocamina_id',
        'tipo_mineral',
        'presentacion',
        'cantidad',
        'peso_bruto',
        'tara',
        'peso_neto',
        'ley_mineral',
        'precio_unitario',
        'total_vendido',
        'comprador',
        'comprador_id',
        'caja_id',
        'observaciones',
        'user_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    public function bocamina()
    {
        return $this->belongsTo(Bocamina::class);
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    public function compradorRelacion()
    {
        return $this->belongsTo(Comprador::class, 'comprador_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ingreso()
    {
        return $this->hasOne(Ingreso::class, 'venta_carga_id');
    }
}
