<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduccionMinera extends Model
{
    use HasFactory;

    protected $table = 'producciones_mineras';

    protected $fillable = [
        'fecha',
        'bocamina_id',
        'veta_sector',
        'tipo_mineral',
        'cargas_extraidas',
        'toneladas_estimadas',
        'observaciones',
        'user_id',
    ];

    public function bocamina()
    {
        return $this->belongsTo(Bocamina::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
