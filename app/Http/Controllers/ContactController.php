<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        // Aqui você pode implementar a lógica para enviar o email ou salvar a solicitação no banco de dados
        // Exemplo de envio de e-mail usando Mail (se você configurar o envio de e-mails no Laravel)
        $user = auth()->user();

        Mail::to('matheus.martins@c3saude.com.br')->send(
            new \App\Mail\SupportContactMail([
                'name' => $user->name,
                'email' => $user->email,
                'message' => $validated['message'],
            ])
        );
        
        // Redireciona com uma mensagem de sucesso
        return redirect()->route('faq')->with('success', 'Sua mensagem foi enviada com sucesso!');
    }
}
