<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesLogisticasDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_logisticas_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roles_logistica_id', 50);
            $table->foreignId('material_id');
            $table->foreignId('sitio_id');
            $table->foreignId('sucursal_id')->nullable();
            $table->foreignId('bodega_id')->nullable();
            $table->foreignId('cliente_id')->nullable();
            $table->foreignId('usuario_creacion_id')->nullable();
            $table->foreignId('usuario_actualizacion_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('cliente_id')->references('id')->on('cat_clientes');
            $table->foreign('material_id')->references('id')->on('cat_materiales');
            $table->foreign('sucursal_id')->references('id')->on('cat_sucursales');
            $table->foreign('bodega_id')->references('id')->on('cat_bodegas');
            $table->foreign('usuario_creacion_id')->references('id')->on('users');
            $table->foreign('usuario_actualizacion_id')->references('id')->on('users');
            // CAMPOS RECOLECCION PARA SUPERVISOR DE PLANTA ALH 
            $table->string('folio_planta', 50)->nullable();
            $table->string('folio_recoleccion', 50)->nullable();
            $table->double('peso_tara_planta', 8, 2)->nullable();
            $table->double('peso_bruto_planta', 8, 2)->nullable();
            $table->double('total_planta', 8, 2)->nullable();
            $table->double('recoleccion_asitencia')->nullable();
            $table->dateTime('hora_llegada_planta', 0)->nullable();
            $table->dateTime('hora_salida_planta', 0)->nullable();
            $table->foreignId('usuario_creacion_planta_id')->nullable();
            $table->foreignId('usuario_actualizacion_planta_id')->nullable();
            $table->foreign('usuario_creacion_planta_id')->references('id')->on('users')->nullable();
            $table->foreign('usuario_actualizacion_planta_id')->references('id')->on('users')->nullable();
            //Bodega
            $table->double('peso_tara_bodega', 8, 2)->default(0);
            $table->double('peso_bruto_bodega', 8, 2)->default(0);
            $table->double('total_bodega', 8, 2)->default(0);
            $table->dateTime('hora_llegada_bodega', 0)->nullable();
            $table->dateTime('hora_salida_bodega', 0)->nullable();
            
            $table->foreignId('usuario_actualizacion_bodega_id')->nullable();
            $table->foreign('usuario_actualizacion_bodega_id')->references('id')->on('users')->nullable();
            // Validacion finanzas
            $table->double('peso_tara_validacion', 8, 2)->default(0);
            $table->double('peso_bruto_validacion', 8, 2)->default(0);
            $table->double('total_validacion', 8, 2)->default(0);
            $table->text('observaciones_recoleccion')->nullable();
            $table->text('observaciones_validacion')->nullable();
            $table->char('entrada_correcta',2)->default('NO');
            $table->text('observaciones_bodega')->nullable();
            $table->foreignId('usuario_validacion_id')->nullable();
            $table->foreign('usuario_validacion_id')->references('id')->on('users')->nullable();
            $table->foreignId('estatus_detalle_id')->nullable();
            $table->foreign('estatus_detalle_id')->references('id')->on('estatus_detalles')->nullable();
            $table->char('validacion_correcta',2)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_logisticas_detalle');
    }
}
