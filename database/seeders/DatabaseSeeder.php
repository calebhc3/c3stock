<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Executa os seeders em ordem lógica
        $this->call([
            TeamAndUserSeeder::class,
            InsumoSeeder::class,
        ]);
    }
}
