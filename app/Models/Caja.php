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

    public function anticipos()
    {
        return $this->hasMany(Anticipo::class, 'caja_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'caja_id');
    }

    public function ventaCargas()
    {
        return $this->hasMany(VentaCarga::class, 'caja_id');
    }

    public static function getFondoPersonal()
    {
        return self::where('tipo', 'caja_chica')
            ->orWhere('nombre', 'LIKE', '%Personal%')
            ->first() ?? self::first();
    }

    public static function getFondoOperativo()
    {
        return self::where('tipo', 'caja_general')
            ->orWhere('nombre', 'LIKE', '%Operat%')
            ->orWhere('nombre', 'LIKE', '%Comercial%')
            ->first() ?? self::first();
    }
}
