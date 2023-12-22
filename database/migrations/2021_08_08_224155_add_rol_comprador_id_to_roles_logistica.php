<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRolCompradorIdToRolesLogistica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_logistica', function (Blueprint $table) {
            $table->unsigneInteger('rol_comprador_id')->after('vehiculo_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles_logistica', function (Blueprint $table) {
            $table->dropColumn(['rol_comprador_id']);
        });
    }
}
