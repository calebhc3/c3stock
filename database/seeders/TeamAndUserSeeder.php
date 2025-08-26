<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamAndUserSeeder extends Seeder
{
    private const TOTAL_EDITORES = 3;
    private const DEFAULT_PASSWORD = 'password123';
    public function run(): void
    {
        $teamsData = collect([
            ['cnpj' => '42.446.277/0003-54', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SOC-BA2 SIMOES FILHO', 'cidade' => 'SIMOES FILHO', 'estado' => 'BA', 'cep' => '43715-795', 'name' => 'SIMOES FILHO'],
            ['cnpj' => '42.446.277/0014-07', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SOC-GO01 GOIANIA', 'cidade' => 'HIDROLÂNDIA', 'estado' => 'GO', 'cep' => '75340-000', 'name' => 'HIDROLÂNDIA'],
            ['cnpj' => '42.446.277/0002-73', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SOC-MG-02 BETIM', 'cidade' => 'BETIM', 'estado' => 'MG', 'cep' => '32631-075', 'name' => 'BETIM'],
            ['cnpj' => '42.446.277/0150-33', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL', 'cidade' => 'DUQUE DE CAXIAS', 'estado' => 'RJ', 'cep' => '25.065-004', 'name' => 'DUQUE DE CAXIAS'],
            ['cnpj' => '42.446.277/0109-02', 'razao_social' => 'SHPX LOGISTICA SOC-PE2 JABOATAO DOS GUARARAPES', 'cidade' => 'JABOATÃO DOS GUARARAPES', 'estado' => 'PE', 'cep' => '54335-000', 'name' => 'CABO DE SANTO AGOSTINHO'],
            ['cnpj' => '42.446.277/0029-93', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL CAMPINA GDE DO SUL SOC', 'cidade' => 'CAMPINA GRANDE DO SUL', 'estado' => 'PR', 'cep' => '83430-000', 'name' => 'CAMPINA GRANDE DO SUL'],
            ['cnpj' => '42.446.277/0112-08', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SJM LM RIO1 SOC RJ', 'cidade' => 'SÃO JOÃO DE MERITI', 'estado' => 'RJ', 'cep' => '25585-000', 'name' => 'SAO JOAO DE MERITI'],
            ['cnpj' => '42.446.277/0184-82', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SOC-RS1 GRAVATAI', 'cidade' => 'GRAVATAÍ', 'estado' => 'RS', 'cep' => '94100-420', 'name' => 'GRAVATAI'],
            ['cnpj' => '42.446.277/0188-06', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SANTANA PARNA SOC', 'cidade' => 'SANTANA DE PARNAÍBA', 'estado' => 'SP', 'cep' => '06524-115', 'name' => 'SANTANA DE PARNAIBA'],
            ['cnpj' => '42.446.277/0144-95', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SOC-SP7 LOUVEIRA', 'cidade' => 'LOUVEIRA', 'estado' => 'SP', 'cep' => '13290-150', 'name' => 'LOUVEIRA'],
            ['cnpj' => '42.446.277/0125-22', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SOC-SP05 CRAVINHOS', 'cidade' => 'CRAVINHOS', 'estado' => 'SP', 'cep' => '14140-000', 'name' => 'CRAVINHOS'],
            ['cnpj' => '42.446.277/0146-57', 'razao_social' => 'SHPX LOGISTICA LTDA SOC-SP9 FRANCO DA ROCHA', 'cidade' => 'FRANCO DA ROCHA', 'estado' => 'SP', 'cep' => '07834-000', 'name' => 'FRANCO DA ROCHA'],
            ['cnpj' => '42.446.277/0018-30', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SOC-SP06 GUARULHOS', 'cidade' => 'GUARULHOS', 'estado' => 'SP', 'cep' => '07251-500', 'name' => 'GUARULHOS'],
            ['cnpj' => '42.446.277/0001-92', 'razao_social' => 'SHPX LOGISTICA LTDA.', 'cidade' => 'SÃO PAULO', 'estado' => 'SP', 'cep' => '00000-000', 'name' => 'MATRIZ B32'],
            ['cnpj' => '42.446.277/0001-91', 'razao_social' => 'SHPX LOGISTICA LTDA.', 'cidade' => 'SÃO PAULO', 'estado' => 'SP', 'cep' => '00000-000', 'name' => 'MATRIZ FLP'],
            ['cnpj' => '42.446.277/0001-91', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SOC-SP10 IBITINGA', 'cidade' => 'IBITINGA', 'estado' => 'SP', 'cep' => '00000-000', 'name' => 'IBITINGA'],
            ['cnpj' => '42.446.277/0001-91', 'razao_social' => 'SHPX LOGISTICA LTDA FILIAL SOC-SC1 ITAJAÍ', 'cidade' => 'ITAJAÍ', 'estado' => 'SC', 'cep' => '00000-000', 'name' => 'ITAJAÍ'],
            ['cnpj' => '42.446.277/0001-91', 'razao_social' => 'SHPX LOGISTICA LTDA.', 'cidade' => 'SÃO BERNARDO DO CAMPO', 'estado' => 'SP', 'cep' => '00000-000', 'name' => 'SÃO BERNARDO DO CAMPO'],
        ]);

        // Criar usuário owner (administrador geral)
        $owner = User::firstOrCreate(
            ['email' => 'owner@c3stock.com'],
            [
                'name' => 'Administrador Geral',
                'cargo' => 'Administrador Master',
                'password' => Hash::make(self::DEFAULT_PASSWORD),
                'email_verified_at' => now(),
            ]
        );

        $teamsData->each(function ($data) use ($owner) {
            $team = Team::firstOrCreate(
                ['cnpj' => $data['cnpj']],
                [
                    'user_id' => $owner->id,
                    'razao_social' => $data['razao_social'],
                    'cidade' => $data['cidade'],
                    'estado' => $data['estado'],
                    'cep' => $data['cep'],
                    'personal_team' => false,
                    'name' => $data['name']
                ]
            );

            $baseEmail = $this->sanitizeForEmail($data['name']);

            // Admin da unidade
            $admin = User::firstOrCreate(
                ['email' => "{$baseEmail}01@c3stock.com"],
                [
                    'name' => "Enfermeira {$data['name']}",
                    'cargo' => 'Enfermeira da Unidade',
                    'password' => Hash::make(self::DEFAULT_PASSWORD),
                    'email_verified_at' => now(),
                ]
            );
            $admin->teams()->attach($team->id, ['role' => 'admin']);
            $admin->current_team_id = $team->id;
            $admin->save();
            // Editores
            for ($i = 1; $i <= self::TOTAL_EDITORES; $i++) {
                $numero = Str::padLeft($i + 1, 2, '0');
                $editor = User::firstOrCreate(
                    ['email' => "{$baseEmail}{$numero}@c3stock.com"],
                    [
                        'name' => "Editor {$i} {$data['name']}",
                        'cargo' => 'Técnica de Enfermagem da Unidade',
                        'password' => Hash::make(self::DEFAULT_PASSWORD),
                        'email_verified_at' => now(),
                    ]
                );
                $editor->teams()->attach($team->id, ['role' => 'editor']);
                $editor->current_team_id = $team->id;
                $editor->save();
            }
        });

        // Associar o owner ao primeiro time como admin
        $firstTeam = Team::first();
        $owner->teams()->syncWithoutDetaching([
            $firstTeam->id => ['role' => 'admin'],
        ]);
        $owner->current_team_id = $firstTeam->id;
        $owner->save();
    }

    private function sanitizeForEmail(string $string): string
    {
        return Str::slug($string, '');
    }
}