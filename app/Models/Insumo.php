<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team;

class Insumo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'tipo',
        'unidade_medida',
    ];

    public function necessarioComprar($teamId)
    {
        $pivot = $this->estoques()->where('team_id', $teamId)->first()?->pivot;
        return $pivot ? max($pivot->quantidade_minima - $pivot->quantidade_existente, 0) : null;
    }
    public function getNecessarioComprarAttribute()
    {
        $pivot = $this->pivot;
        if (!$pivot) return 0;
    
        return round(max(0, $pivot->quantidade_minima*1.2 - $pivot->quantidade_existente));
    }
    public function estoques()
    {
        return $this->belongsToMany(Team::class, 'insumo_team')
            ->withPivot('quantidade_existente', 'quantidade_minima', 'unidades_por_pacote')
            ->withTimestamps();
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
}
