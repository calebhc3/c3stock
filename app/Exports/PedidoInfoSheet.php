<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;

class PedidoInfoSheet implements FromCollection
{
    protected $usuario;
    protected $equipe;
    protected $pedido_id;

    public function __construct(string $usuario, string $equipe, int $pedido_id)
    {
        $this->usuario = $usuario;
        $this->equipe = $equipe;
        $this->pedido_id = $pedido_id;
    }

    public function collection(): Collection
    {
        return collect([
            ['Número do Pedido', $this->pedido_id],
            ['Solicitante', $this->usuario],
            ['Equipe', $this->equipe],
            ['Data do Pedido', Carbon::now()->format('d/m/Y H:i')],
        ]);
    }
}
