<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntregaClienteToRolesCompradores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_compradores', function (Blueprint $table) {
            $table->unsigneInteger('entrega_domicilio')->after('hora_llegada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles_compradores', function (Blueprint $table) {
            $table->dropColumn(['entrega_domicilio']);
        });
    }
}
