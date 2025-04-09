<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamAndUserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 37; $i++) {
            // Cria o dono do time
// Cria o dono do time
$owner = User::create([
    'name' => "Team{$i} Owner",
    'email' => "owner{$i}@example.com",
    'password' => Hash::make('password'),
]);

// Cria o time
$team = Team::forceCreate([
    'user_id' => $owner->id,
    'name' => "Equipe {$i}",
    'personal_team' => false,
]);

// ⚠️ Setar o time atual no usuário dono
$owner->current_team_id = $team->id;
$owner->save();

// Associa o time ao dono com role de admin
$owner->teams()->attach($team, ['role' => 'admin']);

// Adiciona mais 2 usuários no time
for ($j = 1; $j <= 2; $j++) {
    $member = User::create([
        'name' => "Team{$i} User{$j}",
        'email' => "team{$i}_user{$j}@example.com",
        'password' => Hash::make('password'),
    ]);

    // Adiciona como membro com role de editor
    $team->users()->attach($member, ['role' => 'editor']);
}

        }
    }
}
