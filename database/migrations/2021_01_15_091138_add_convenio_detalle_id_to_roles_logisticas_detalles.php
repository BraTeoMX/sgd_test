<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConvenioDetalleIdToRolesLogisticasDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_logisticas_detalle', function (Blueprint $table) {
            $table->unsignedBigInteger('convenio_detalle_id')->after('roles_logistica_id');            
            $table->foreign('convenio_detalle_id')->references('id')->on('convenios_detalles');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles_logisticas_detalle', function (Blueprint $table) {
            $table->dropForeign(['convenio_detalle_id']);
            $table->dropColumn(['convenio_detalle_id']);          
        });
    }
}
