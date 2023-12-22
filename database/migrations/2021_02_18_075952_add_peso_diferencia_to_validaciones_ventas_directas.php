<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPesoDiferenciaToValidacionesVentasDirectas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('validaciones_ventas_directas', function (Blueprint $table) {
            $table->float('peso_diferencia')->after('rol_logistica_detalle_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('validaciones_ventas_directas', function (Blueprint $table) {
            $table->dropColumn('peso_diferencia');
        });
    }
}
