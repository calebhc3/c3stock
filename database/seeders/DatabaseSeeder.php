<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Insumo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TeamAndUserSeeder::class,
            InsumoSeeder::class,
        ]);
    }
}
