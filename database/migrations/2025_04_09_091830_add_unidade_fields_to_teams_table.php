<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('cnpj')->nullable();
            $table->string('nome_unidade')->nullable();
            $table->string('razao_social')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep')->nullable();
        });
    }
    
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn([
                'cnpj',
                'nome_unidade',
                'razao_social',
                'cidade',
                'estado',
                'cep',
            ]);
        });
    }
    
};
