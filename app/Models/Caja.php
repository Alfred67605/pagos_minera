<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    protected $table = 'cajas';

    protected $fillable = [
        'nombre',
        'tipo',
        'saldo_inicial',
        'saldo_actual',
        'estado',
    ];

    public function movimientos()
    {
        return $this->hasMany(CajaMovimiento::class, 'caja_id');
    }

    public function egresos()
    {
        return $this->hasMany(Egreso::class, 'caja_id');
    }
}
