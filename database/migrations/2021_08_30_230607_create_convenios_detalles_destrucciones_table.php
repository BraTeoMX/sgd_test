<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConveniosDetallesDestruccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convenios_detalles_destrucciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convenio_id');
            $table->unsignedBigInteger('convenio_detalle_id');
            $table->unsignedBigInteger('material_id');
            $table->float('precio_sin_iva');
            $table->float('iva');
            $table->float('iva_retenido')->nullable();
            $table->float('precio_pagar');
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->unsignedBigInteger('usuario_creacion_id')->nullable();
            $table->unsignedBigInteger('usuario_actualizacion_id')->nullable(); 
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('convenio_id')->references('id')->on('convenios');
            $table->foreign('material_id')->references('id')->on('cat_materiales');
            $table->foreign('sucursal_id')->references('id')->on('cat_sucursales');
            $table->foreign('usuario_creacion_id')->references('id')->on('users');
            $table->foreign('usuario_actualizacion_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('convenios_detalles_destrucciones');
    }
}
