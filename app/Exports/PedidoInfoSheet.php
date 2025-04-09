<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PedidoInfoSheet implements FromCollection, WithStyles, WithColumnWidths
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

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // Estilo geral (todas as células com borda e fonte calibri)
        $sheet->getStyle("A1:B{$highestRow}")->applyFromArray([
            'font' => ['name' => 'Calibri', 'size' => 12],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => ['vertical' => 'center'],
        ]);

        // Coluna A (títulos) com fundo verde escuro e texto branco
        $sheet->getStyle("A1:A{$highestRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '04724D'],
            ],
            'alignment' => ['horizontal' => 'left'],
        ]);

        // Coluna B (valores) com fundo leve
        $sheet->getStyle("B1:B{$highestRow}")->applyFromArray([
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'F5FFFA'], // quase branco, toque suave de verde
            ],
        ]);

        return [];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 20,
        ];
    }
}
