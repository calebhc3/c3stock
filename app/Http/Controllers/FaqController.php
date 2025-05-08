<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        // Dados do FAQ
        $faqItems = [
            [
                'question' => 'Como controlar o estoque?',
                'answer' => 'O controle de estoque pode ser feito acessando a página "Estoque de Insumos". Lá você verá os itens cadastrados, com a quantidade disponível, mínima, sugerida e faltante.'
            ],
            [
                'question' => 'Como solicitar insumos?',
                'answer' => 'Para solicitar insumos, basta ir à seção "Fazer Solicitação" e adicionar um pedido de insumos para seu ambulatório. Você pode personalizar a quantidade e os itens.'
            ],
            [
                'question' => 'Como visualizar o painel da dashboard?',
                'answer' => 'Na página inicial, você pode visualizar métricas importantes do sistema. O painel apresenta as últimas ações no estoque e insumos em falta.'
            ]
            // Adicione mais perguntas conforme necessário
        ];

        return view('faq.index', compact('faqItems'));
    }
}
