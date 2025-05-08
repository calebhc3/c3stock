<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_unidades_por_pacote_to_insumo_team.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnidadesPorPacoteToInsumoTeam extends Migration
{
    public function up()
    {
        Schema::table('insumo_team', function (Blueprint $table) {
            $table->integer('unidades_por_pacote')->default(1)->after('quantidade_existente');
        });
    }

    public function down()
    {
        Schema::table('insumo_team', function (Blueprint $table) {
            $table->dropColumn('unidades_por_pacote');
        });
    }
}
