<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_create_pedidos_table.php

public function up()
{
    Schema::create('pedidos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('file_path');
        $table->string('status')->default('pendente'); // pendente, aprovado, rejeitado
        $table->text('observacoes')->nullable(); // Observações adicionais sobre o pedido
        $table->date('data_entrega')->nullable(); // Data prevista para entrega do pedido

        // Timestamps padrão
        $table->timestamps(); // created_at será a data do pedido
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
