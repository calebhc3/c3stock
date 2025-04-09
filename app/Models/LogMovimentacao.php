<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Team; // Se estiver usando Jetstream Teams

class LogMovimentacao extends Model
{
    use HasFactory;

    protected $table = 'log_movimentacoes';

    protected $fillable = ['user_id', 'insumo_id', 'tipo_acao', 'quantidade', 'quantidade_final', 'team_id',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
}
