<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConvenioDetalleIdVentaDirecta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas_directas', function (Blueprint $table) {
            $table->unsignedInteger('convenio_detalle_id')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas_directas', function (Blueprint $table) {
            $table->dropColumn(['convenio_detalle_id']);
        });
    }
}
