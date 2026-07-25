<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaEgreso extends Model
{
    use HasFactory;

    protected $table = 'categoria_egresos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function egresos()
    {
        return $this->hasMany(Egreso::class, 'categoria_id');
    }
}
