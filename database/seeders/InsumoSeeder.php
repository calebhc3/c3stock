<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Insumo;
use App\Models\Team;

class InsumoSeeder extends Seeder
{
    private const ITEMS = [
        'medicamentos' => [
        'Bilastina' => 'unidade',
        'Captopril 25 MG' => 'comprimido',
        'Cimegrip' => 'unidade',
        'Cinarizina 25 mg' => 'comprimido',
        'Diclofenaco Spray' => 'unidade',
        'Dipirona c/10 unid' => 'cartela',
        'Diprogenta Pomada' => 'unidade',
        'Eno' => 'sachê',
        'Epocler' => 'unidade',
        'Escopolamina 10 mg sem dipirona c\ 20 comp' => 'cartela',
        'Furosemida' => 'comprimido',
        'Glicose 25% ampolas' => 'ampola',
        'Ibuprofeno' => 'comprimido',
        'Luftal - Simeticona' => 'comprimido',
        'Mylanta Plus' => 'unidade',
        'Nebacetin Pomada' => 'unidade',
        'Paracetamol' => 'comprimido',
        'Pasalix' => 'unidade',
        'Prednisolona Comprimido' => 'comprimido',
        'Racecadotrila (diarreia persistente)' => 'comprimido',
        'Sais de reidratação oral' => 'sachê',
        'Torsilax' => 'comprimido',
        'Vonau' => 'comprimido',
    ],
    'ps_ambulatorio' => [
        'Álcool 70% (100ml)' => 'frasco',
        'Atadura crepom 10cm X 1,5m' => 'unidade',
        'Atadura crepom 15cm X 1,5m' => 'unidade',
        'Atadura crepom 20cm X 1,5m' => 'unidade',
        'Clorexidina Degermante 2% (100ml)' => 'frasco',
        'Curativo transparente (Band-Aid)' => 'unidade',
        'Fita de dextro (on call plus II) c/50' => 'rolo',
        'Gaze 13 fios 7,5cm x 7,5cm Estéril' => 'pacote',
        'Anti séptico Spray' => 'unidade',
        'Luva de procedimento cirúrgico' => 'par',
        'Máscara Cirúrgica' => 'unidade',
        'Soro Fisiológico 0,9% 100 ml (bolsa)' => 'bolsa',
        'Termômetro' => 'unidade',
        'Micropore' => 'rolo',
        'Esparadrapo' => 'rolo',
        'Diclofenaco Spray' => 'unidade',
    ],
    'ps_maleta' => [
        'Álcool 70% (100ml)' => 'frasco',
        'Atadura crepom 10cm X 1,5m' => 'unidade',
        'Atadura crepom 15cm X 1,5m' => 'unidade',
        'Atadura crepom 20cm X 1,5m' => 'unidade',
        'Clorexidina Degermante 2% (100ml)' => 'frasco',
        'Curativo transparente (Band-Aid)' => 'unidade',
        'Gaze 13 fios 7,5cm x 7,5cm Estéril' => 'pacote',
        'Anti séptico Spray' => 'unidade',
        'Luva de procedimento cirúrgico' => 'par',
        'Máscara Cirúrgica' => 'unidade',
        'Soro Fisiológico 0,9% 100 ml (bolsa)' => 'bolsa',
        'Termômetro' => 'unidade',
        'Micropore' => 'rolo',
        'Esparadrapo' => 'rolo',
        'Maleta' => 'unidade',
    ],
    'insumos_equipamentos' => [
        'Bateria 2032 eugin' => 'unidade',
        'Abaixador de língua' => 'unidade',
        'Fita Métrica' => 'unidade',
        'Oxímetro de Pulso' => 'unidade',
        'Balança Digital' => 'unidade',
        'Aparelho de P.A Analógico com braçadeira GG' => 'unidade',
        'Estetoscópio' => 'unidade',
        'Termômetro digital' => 'unidade',
        'Maleta de Primeiros Socorros' => 'unidade',
        'Lacres com números para controle' => 'pacote',
        'DEA' => 'unidade',
        'Suporte para o DEA' => 'unidade',
        'Gaze 13 fios 7,5cm x 7,5cm Estéril' => 'pacote',
        'Algodão hidrofílico bolinha' => 'pacote',
        'Micropore' => 'rolo',
        'Esparadrapo' => 'rolo',
        'Termogel Bolsa P/ Compressa' => 'unidade',
        'Tesoura sem ponta' => 'unidade',
    ],
];
    public function run(): void
    {
        // Get all teams that were created by TeamAndUserSeeder
        $teams = Team::all();
        
        if ($teams->isEmpty()) {
            $this->command->error('No teams found! Please run TeamAndUserSeeder first.');
            return;
        }

        foreach (self::ITEMS as $tipo => $items) {
            foreach ($items as $nome => $unidadeMedida) {
                $insumo = Insumo::firstOrCreate(
                    ['nome' => $nome],
                    [
                        'tipo' => $tipo,
                        'unidade_medida' => $unidadeMedida
                    ]
                );

                foreach ($teams as $team) {
                    $this->createStockRecord($insumo, $team, $tipo, $unidadeMedida);
                }
            }
        }

        $this->command->info('Insumos seeded successfully for all teams!');
    }

    private function createStockRecord(Insumo $insumo, Team $team, string $tipo, string $unidadeMedida): void
    {
        if (DB::table('insumo_team')
            ->where('insumo_id', $insumo->id)
            ->where('team_id', $team->id)
            ->exists()) {
            return;
        }

        DB::table('insumo_team')->insert([
            'insumo_id' => $insumo->id,
            'team_id' => $team->id,
            'quantidade_minima' => $this->generateMinimumQuantity($tipo),
            'quantidade_existente' => $this->generateInitialStock($tipo),
            'unidades_por_pacote' => $this->generatePackageSize($unidadeMedida),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function generateMinimumQuantity(string $tipo): int
    {
        return $tipo === 'medicamentos' ? rand(10, 30) : rand(5, 20);
    }

    private function generateInitialStock(string $tipo): int
    {
        $min = $this->generateMinimumQuantity($tipo);
        return rand((int) floor($min * 0.5), (int) floor($min * 0.7));
    }


    private function generatePackageSize(string $unidadeMedida): int
    {
        return match($unidadeMedida) {
            'cartela', 'pacote' => rand(10, 50),
            'frasco', 'bolsa' => rand(5, 12),
            default => 1
        };
    }
}