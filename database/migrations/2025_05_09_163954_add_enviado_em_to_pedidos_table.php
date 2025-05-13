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
        Schema::table('pedidos', function (Blueprint $table) {
        $table->timestamp('enviado_em')->nullable();
        $table->boolean('enviado')->default(false);
        $table->timestamp('recebido_em')->nullable()->after('enviado_em');
        $table->timestamp('data_envio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['enviado_em', 'recebido_em']);
            $table->dropColumn('enviado');
            $table->dropColumn('data_envio');
        });
    }
};
