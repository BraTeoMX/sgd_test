<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_logistica_detalle_id')->nullable();
            $table->foreignId('material_id');
            $table->foreignId('sucursal_id');
            $table->foreignId('unidad_medida_inventario');
            $table->foreignId('bodega_id');
            $table->decimal('existencia_inicial', 12, 2);
            $table->decimal('entradas', 12, 2);
            $table->decimal('salidas', 12, 2);
            $table->decimal('stock', 12, 2);
            $table->foreignId('usuario_creacion_id');
            $table->foreignId('usuario_modificacion_id')->nullable();
            $table->timestamps();
            $table->foreign('material_id')->references('id')->on('cat_materiales');
            $table->foreign('sucursal_id')->references('id')->on('cat_sucursales');
            $table->foreign('bodega_id')->references('id')->on('cat_bodegas');
            $table->foreign('usuario_creacion_id')->references('id')->on('users');
            $table->foreign('usuario_modificacion_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventarios');
    }
}
