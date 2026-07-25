<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;

    protected $table = 'egresos';

    protected $fillable = [
        'caja_id',
        'categoria_id',
        'monto',
        'concepto',
        'fecha',
        'comprobante_numero',
        'proveedor',
        'observaciones',
        'user_id',
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaEgreso::class, 'categoria_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

