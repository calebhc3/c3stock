<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItensPedidoSheet implements FromCollection, WithHeadings
{
    protected $itens;

    public function __construct(array $itens)
    {
        $this->itens = $itens;
    }

    public function headings(): array
    {
        return ['Insumo', 'Quantidade'];
    }

    public function collection(): Collection
    {
        return collect($this->itens)->map(function ($item) {
            return [
                $item['nome'],
                $item['quantidade']
            ];
        });
    }
}
