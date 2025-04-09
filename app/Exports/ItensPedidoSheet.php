<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ItensPedidoSheet implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
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

    public function styles(Worksheet $sheet)
    {
        // Cabeçalhos
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '04724D'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Estilo geral da planilha
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:B{$highestRow}")->applyFromArray([
            'font' => ['name' => 'Calibri', 'size' => 11],
            'alignment' => ['horizontal' => 'center'],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Linhas zebradas
        for ($row = 2; $row <= $highestRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'E9F8F1'], // Verde claro C3
                    ],
                ]);
            }
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 20,
        ];
    }
}
