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
            $table->string('endereco_unidade')->nullable();
            $table->string('bairro_unidade')->nullable();
            $table->string('cidade_unidade')->nullable();
            $table->string('estado_unidade')->nullable();
            $table->string('cep_unidade')->nullable();
        });
    }
    
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn([
                'cnpj',
                'nome_unidade',
                'endereco_unidade',
                'bairro_unidade',
                'cidade_unidade',
                'estado_unidade',
                'cep_unidade',
            ]);
        });
    }
    
};
