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
        Schema::table('properties', function (Blueprint $table) {
            // Adiciona a coluna 'order'
            // Default 9999 garante que imóveis novos ou sem ordem definida
            // fiquem no final da lista, não atrapalhando os que você já organizou no topo.
            $table->integer('order')->default(9999)->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};