<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameClienteToRolesLogistica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_logistica', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->renameColumn('cliente_id', 'convenio_id');
            $table->foreign('convenio_id')->references('id')->on('convenios');
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
            $table->dropForeign(['cliente_id']);
            $table->renameColumn('cliente_id', 'convenio_id');
            $table->foreign('convenio_id')->references('id')->on('convenios');
        });
    }
}
