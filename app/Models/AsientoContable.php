<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsientoContable extends Model
{
    use HasFactory;

    protected $table = 'asientos_contables';

    protected $fillable = [
        'numero_asiento',
        'fecha',
        'glosa',
        'debe_total',
        'haber_total',
        'user_id',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleAsientoContable::class, 'asiento_contable_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
