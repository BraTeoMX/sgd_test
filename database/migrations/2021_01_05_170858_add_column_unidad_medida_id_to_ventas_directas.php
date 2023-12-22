<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUnidadMedidaIdToVentasDirectas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas_directas', function (Blueprint $table) {
            $table->foreignId('unidad_medida_id')->nullable()->after('rol_logistica_detalle_id');
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
            $table->dropColumn('unidad_medida_id');
        });
    }
}
