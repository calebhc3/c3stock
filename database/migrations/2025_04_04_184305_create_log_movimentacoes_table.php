<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('log_movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quem fez a ação
            $table->foreignId('insumo_id')->constrained()->onDelete('cascade'); // Qual insumo foi movimentado
            $table->enum('tipo_acao', ['entrada', 'saida', 'edicao']); // O que foi feito
            $table->integer('quantidade'); // Quantidade movimentada
            $table->integer('quantidade_final'); // Como ficou o estoque
            $table->timestamps(); // Quando aconteceu
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_movimentacoes');
    }
};
