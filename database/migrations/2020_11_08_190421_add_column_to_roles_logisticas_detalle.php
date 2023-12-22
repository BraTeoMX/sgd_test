<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRolesLogisticasDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_logisticas_detalle', function (Blueprint $table) {
            $table->integer('kilometraje_recoleccion')->nullable()->after('cliente_id');
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
            $table->dropColumn('kilometraje_recoleccion');
        });
    }
}
