<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedidoInsumos extends Mailable
{
    use Queueable, SerializesModels;

    public $itens;
    public $pedido_id;
    public $usuario;
    public $equipe;

    public function __construct(array $itens, int $pedido_id, string $usuario, string $equipe)
    {
        $this->itens = $itens;
        $this->pedido_id = $pedido_id;
        $this->usuario = $usuario;
        $this->equipe = $equipe;
    }

    public function build()
    {
        return $this->subject("📋 Pedido de Insumos #{$this->pedido_id}")
                    ->view('emails.pedido_insumos')
                    ->with([
                        'itens' => $this->itens,
                        'pedido_id' => $this->pedido_id,
                        'usuario' => $this->usuario,
                        'equipe' => $this->equipe
                    ]);
    }
}
