<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    use HasFactory;

    protected $table = 'socios';

    protected $fillable = [
        'codigo',
        'ci',
        'nombre',
        'telefono',
        'bocamina_id',
        'porcentaje_participacion',
        'estado',
        'observaciones',
    ];

    public function bocamina()
    {
        return $this->belongsTo(Bocamina::class, 'bocamina_id');
    }

    public function anticipos()
    {
        return $this->hasMany(Anticipo::class, 'socio_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'socio_id');
    }

    public function ventas()
    {
        return $this->hasMany(VentaCarga::class, 'socio_id');
    }
}
