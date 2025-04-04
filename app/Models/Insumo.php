<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'tipo',
        'quantidade_minima',
        'unidade_medida',
        'quantidade_existente',
    ];

    // Accessor para o campo "necessario_comprar"
    public function getNecessarioComprarAttribute()
    {
        return max($this->quantidade_minima - $this->quantidade_existente, 0);
    }
}
