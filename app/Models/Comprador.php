<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprador extends Model
{
    use HasFactory;

    protected $table = 'compradores';

    protected $fillable = [
        'razon_social',
        'nit_ci',
        'contacto_nombre',
        'telefono',
        'email',
        'direccion',
        'notas',
        'estado',
    ];

    public function ventas()
    {
        return $this->hasMany(VentaCarga::class, 'comprador_id');
    }
}
