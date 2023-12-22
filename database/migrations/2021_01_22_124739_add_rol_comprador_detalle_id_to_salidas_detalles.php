<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRolCompradorDetalleIdToSalidasDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salidas_detalles', function (Blueprint $table) {
            $table->dropColumn(['salida_id']);
            $table->unsignedBigInteger('rol_comprador_detalle_id')->after(' id')->nullable();
            $table->foreign('rol_comprador_detalle_id')->references('id')->on('roles_compradores_detalles');
            $table->unsignedBigInteger('inventario_id')->after('rol_comprador_detalle_id')->nullable();
            $table->foreign('inventario_id')->references('id')->on('inventarios');
            $table->string('folio_intimark', 50)->after('material_id');
            $table->decimal('peso_tara', 11, 2)->after('folio_intimark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salidas', function (Blueprint $table) {
            $table->dropForeign(['rol_comprador_detalle_id']);
            $table->dropColumn(['rol_comprador_detalle_id']);
            $table->dropForeign(['inventario_id']);
            $table->dropColumn(['inventario_id']);
        });
    }
}
