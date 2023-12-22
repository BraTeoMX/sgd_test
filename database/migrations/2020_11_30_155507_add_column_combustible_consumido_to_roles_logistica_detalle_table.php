<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCombustibleConsumidoToRolesLogisticaDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_logisticas_detalle', function (Blueprint $table) {
            $table->integer('combustible_consumido')->nullable()->after('kilometraje_recoleccion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles_logistica_detalle', function (Blueprint $table) {
            //
        });
    }
}
