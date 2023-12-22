<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvenioDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convenios_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convenio_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('tipo_material_id');
            $table->string('material_cliente',100);
            $table->unsignedBigInteger('unidad_medida_id');
            $table->float('precio_sin_iva');
            $table->float('iva');
            $table->float('iva_retenido')->nullable();
            $table->float('precio_pagar');
            $table->unsignedBigInteger('frecuencia_id');
            $table->unsignedBigInteger('cobro_servicio')->nullable();
            $table->string('tipo_servicio',100)->nullable();
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('usuario_creacion_id')->nullable();
            $table->unsignedBigInteger('usuario_actualizacion_id')->nullable(); 
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('convenio_id')->references('id')->on('convenios');
            $table->foreign('material_id')->references('id')->on('cat_materiales');
            $table->foreign('tipo_material_id')->references('id')->on('cat_tipo_materiales');
            $table->foreign('unidad_medida_id')->references('id')->on('cat_unidades_medida');
            $table->foreign('frecuencia_id')->references('id')->on('cat_frecuencias');
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
        Schema::dropIfExists('convenios_detalles');
    }
}
