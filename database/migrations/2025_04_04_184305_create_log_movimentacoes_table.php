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
        Schema::create('log_movimentacoes', function (Blueprint $table) {
            $table->id();

            // Chaves estrangeiras
            $table->foreignId('team_id')->constrained()->onDelete('cascade'); // Time dono da movimentação
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quem fez a ação
            $table->foreignId('insumo_id')->constrained()->onDelete('cascade'); // Qual insumo foi movimentado

            // Dados da ação
            $table->enum('tipo_acao', ['entrada', 'saida', 'edicao']);
            $table->integer('quantidade');
            $table->integer('quantidade_final');

            // Timestamps padrão
            $table->timestamps();
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
