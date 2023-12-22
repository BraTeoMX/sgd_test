<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradasInventarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entradas_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable();
            $table->string('folio_entrada_intimark', 50)->nullable();
            $table->foreignId('material_id');
            $table->foreignId('sucursal_id');
            $table->foreignId('bodega_id');
            $table->decimal('inventario_peso_bruto', 11, 2)->nullable();
            $table->decimal('inventario_peso_tara', 11, 2)->nullable();
            $table->decimal('inventario_total', 11, 2);
            $table->char('inventario_afectado', 2)->default('NO')->nullable();
            $table->foreignId('usuario_creacion_id');
            $table->foreignId('usuario_modificacion_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('cat_clientes');
            $table->foreign('material_id')->references('id')->on('cat_materiales');
            $table->foreign('sucursal_id')->references('id')->on('cat_sucursales');
            $table->foreign('bodega_id')->references('id')->on('cat_bodegas');            
            $table->foreign('usuario_creacion_id')->references('id')->on('users');
            $table->foreign('usuario_modificacion_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entradas_inventario');
    }
}
