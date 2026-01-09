<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultants', function (Blueprint $table) {
            // O domínio personalizado (ex: anasilva.pt)
            $table->string('domain')->nullable()->unique()->after('email')->index();
            
            // Slug para URL alternativa (ex: house-team.com/ana-silva) - Opcional mas bom ter
            $table->string('lp_slug')->nullable()->unique()->after('domain');
            
            // Configurações visuais (JSON para guardar cor, título, logo customizado)
            // Isso torna a LP "moldável" como o cliente quer
            $table->json('lp_settings')->nullable()->after('lp_slug');
            
            // Trava de segurança: só acessa se estiver ativo
            $table->boolean('has_lp')->default(false)->after('lp_settings');
        });
    }

    public function down(): void
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->dropColumn(['domain', 'lp_slug', 'lp_settings', 'has_lp']);
        });
    }
};