<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PedidoInsumosExport implements WithMultipleSheets
{
    protected $itens;
    protected $usuario;
    protected $equipe;
    protected $pedido_id;

    public function __construct(array $itens, string $usuario, string $equipe, int $pedido_id)
    {
        $this->itens = $itens;
        $this->usuario = $usuario;
        $this->equipe = $equipe;
        $this->pedido_id = $pedido_id;
    }

    public function sheets(): array
    {
        return [
            new PedidoInfoSheet($this->usuario, $this->equipe, $this->pedido_id),
            new ItensPedidoSheet($this->itens),
        ];
    }
}
