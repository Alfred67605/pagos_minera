<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;

    protected $table = 'ingresos';

    protected $fillable = [
        'fecha',
        'concepto',
        'monto',
        'origen',
        'venta_carga_id',
        'observaciones',
        'user_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function ventaCarga()
    {
        return $this->belongsTo(VentaCarga::class, 'venta_carga_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
