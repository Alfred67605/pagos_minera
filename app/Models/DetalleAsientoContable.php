<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAsientoContable extends Model
{
    use HasFactory;

    protected $table = 'detalle_asientos_contables';

    protected $fillable = [
        'asiento_contable_id',
        'cuenta_contable_id',
        'debe',
        'haber',
    ];

    public function asiento()
    {
        return $this->belongsTo(AsientoContable::class, 'asiento_contable_id');
    }

    public function cuenta()
    {
        return $this->belongsTo(CuentaContable::class, 'cuenta_contable_id');
    }
}
