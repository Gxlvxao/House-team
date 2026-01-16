<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('consultants', function (Blueprint $table) {
            // Alterado para 'email' para garantir que não dá erro
            $table->string('crm_user_id')->nullable()->after('email'); 
        });
    }

    public function down()
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->dropColumn('crm_user_id');
        });
    }
};