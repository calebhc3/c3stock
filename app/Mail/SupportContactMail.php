<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Nova mensagem de contato')
                    ->view('emails.support')
                    ->with([
                        'nome' => $this->data['name'],
                        'email' => $this->data['email'],
                        'mensagem' => $this->data['message'],
                    ]);
    }
}
