<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insumo;
use App\Models\Team;

class InsumoSeeder extends Seeder
{
    public function run()
    {
        $teams = Team::all();
        $insumos = \App\Models\Insumo::all();

        if ($teams->isEmpty()) {
            $this->command->error('Certifique-se de que os times existem antes de rodar este seeder.');
            return;
        }        
        
        $itens = [
            // Insumos
            ['nome' => 'Algodão bolinha', 'tipo' => 'Insumo', 'unidade_medida' => 'pacote', 'quantidade_minima' => 10],
            ['nome' => 'Absorvente Mili Noturno (c/32)', 'tipo' => 'Insumo', 'unidade_medida' => 'caixa', 'quantidade_minima' => 5],
            ['nome' => 'Álcool 70% (100ml)', 'tipo' => 'Insumo', 'unidade_medida' => 'frasco', 'quantidade_minima' => 15],
            ['nome' => 'Álcool 70% Swaab c/200', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 10],
            ['nome' => 'Aparelho de P.A. (Braço)', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 2],
            ['nome' => 'Atadura crepom 10cm X 1,8m', 'tipo' => 'Insumo', 'unidade_medida' => 'rolo', 'quantidade_minima' => 10],
            ['nome' => 'Atadura crepom 15cm X 1,8m', 'tipo' => 'Insumo', 'unidade_medida' => 'rolo', 'quantidade_minima' => 10],
            ['nome' => 'Atadura crepom 20cm X 1,8m', 'tipo' => 'Insumo', 'unidade_medida' => 'rolo', 'quantidade_minima' => 10],
            ['nome' => 'Balanca Digital', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 1],
            ['nome' => 'Bateria 2032 elgin', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 5],
            ['nome' => 'Clorexidina Degermante 2% (100ml)', 'tipo' => 'Insumo', 'unidade_medida' => 'frasco', 'quantidade_minima' => 8],
            ['nome' => 'Clorexidina Aquosa', 'tipo' => 'Insumo', 'unidade_medida' => 'frasco', 'quantidade_minima' => 8],
            ['nome' => 'Clorexidina Alcoolica', 'tipo' => 'Insumo', 'unidade_medida' => 'frasco', 'quantidade_minima' => 8],
            ['nome' => 'Curativo transparente (Band-Aid)', 'tipo' => 'Insumo', 'unidade_medida' => 'caixa', 'quantidade_minima' => 5],
            ['nome' => 'Descarpack 13L', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 10],
            ['nome' => 'Esparadrapo', 'tipo' => 'Insumo', 'unidade_medida' => 'rolo', 'quantidade_minima' => 10],
            ['nome' => 'Fita de dextro (on call plus II) c/50', 'tipo' => 'Insumo', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Fita Métrica', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 2],
            ['nome' => 'Gaze 13 fios 7,5cm x 7,5cm', 'tipo' => 'Insumo', 'unidade_medida' => 'pacote', 'quantidade_minima' => 10],
            ['nome' => 'Glicosímetro', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 2],
            ['nome' => 'Iodopovidona 10%', 'tipo' => 'Insumo', 'unidade_medida' => 'frasco', 'quantidade_minima' => 8],
            ['nome' => 'Lancetas Caixa c/100', 'tipo' => 'Insumo', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Lençol papel hospitalar Maca', 'tipo' => 'Insumo', 'unidade_medida' => 'rolo', 'quantidade_minima' => 10],
            ['nome' => 'Luva de procedimento cirúrgico', 'tipo' => 'Insumo', 'unidade_medida' => 'par', 'quantidade_minima' => 30],
            ['nome' => 'Máscara Cirúrgica', 'tipo' => 'Insumo', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Micropore', 'tipo' => 'Insumo', 'unidade_medida' => 'rolo', 'quantidade_minima' => 10],
            ['nome' => 'Oxímetro de Pulso', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 2],
            ['nome' => 'Soro Fisiológico 0,9% ampola', 'tipo' => 'Insumo', 'unidade_medida' => 'ampola', 'quantidade_minima' => 20],
            ['nome' => 'Soro Fisiológico 0,9% 250 ml', 'tipo' => 'Insumo', 'unidade_medida' => 'frasco', 'quantidade_minima' => 15],
            ['nome' => 'Termogel Bolsa P/ Compressa', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 3],
            ['nome' => 'Termômetro', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 2],
            ['nome' => 'Tesoura sem ponta', 'tipo' => 'Insumo', 'unidade_medida' => 'unidade', 'quantidade_minima' => 3],

            // Medicamentos
            ['nome' => 'Benalet Pastilha', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Bilastina', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 5],
            ['nome' => 'Buscofem', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Buscopan composto', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Cetoprofeno comp', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 5],
            ['nome' => 'Cimegripe', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Decongex', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 5],
            ['nome' => 'Diclofenaco Spray', 'tipo' => 'Medicamento', 'unidade_medida' => 'frasco', 'quantidade_minima' => 5],
            ['nome' => 'Dipirona', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 20],
            ['nome' => 'Diprogenta Pomada', 'tipo' => 'Medicamento', 'unidade_medida' => 'bisnaga', 'quantidade_minima' => 5],
            ['nome' => 'Epocler', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 5],
            ['nome' => 'Floratil (Enterogermina Plus)', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 5],
            ['nome' => 'Ibuprofeno', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Nebacetin Pomada', 'tipo' => 'Medicamento', 'unidade_medida' => 'bisnaga', 'quantidade_minima' => 5],
            ['nome' => 'Nimesulida', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Omeprazol Comprimido', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Paracetamol', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 20],
            ['nome' => 'Prednisolona Comprimido', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Torsilax', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
            ['nome' => 'Trok-N', 'tipo' => 'Medicamento', 'unidade_medida' => 'bisnaga', 'quantidade_minima' => 5],
            ['nome' => 'Vonau', 'tipo' => 'Medicamento', 'unidade_medida' => 'caixa', 'quantidade_minima' => 10],
        ];

        foreach ($itens as $item) {
            $insumo = Insumo::firstOrCreate(
                ['nome' => $item['nome']],
                [
                    'tipo' => $item['tipo'],
                    'unidade_medida' => $item['unidade_medida']
                ]
            );
        
            foreach ($teams as $team) {
                $insumo->estoques()->syncWithoutDetaching([
                    $team->id => [
                        'quantidade_minima' => $item['quantidade_minima'],
                        'quantidade_existente' => 0
                    ]
                ]);
            }
        
            $this->command->info("Insumo '{$item['nome']}' vinculado a todos os times com quantidades definidas.");
        }
        $this->command->info('Todos os insumos foram vinculados a todos os times com quantidades definidas.');        
    }
}