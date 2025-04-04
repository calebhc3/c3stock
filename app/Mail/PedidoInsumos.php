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

    public function __construct($itens, $pedido_id)
    {
        $this->itens = $itens;
        $this->pedido_id = $pedido_id;
    }

    public function build()
    {
        return $this->subject('📋 Novo Pedido de Insumos')
                    ->view('emails.pedido_insumos')
                    ->with([
                        'itens' => $this->itens,
                        'pedido_id' => $this->pedido_id
                    ]);
    }

    /**
     * Define o conteúdo do e-mail.
     */
    public function content(): \Illuminate\Mail\Mailables\Content
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.pedido_insumos'
        );
    }

    /**
     * Anexos, se necessário.
     */
    public function attachments(): array
    {
        return [];
    }
}
