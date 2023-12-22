<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResiduosSegregacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residuos_segregaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_logistica_detalle_id');
            $table->string('folio_residuo', 50)->nullable();
            $table->double('peso_tara_residuo', 8, 2)->nullable();
            $table->double('peso_bruto_residuo', 8, 2)->nullable();
            $table->double('total_residuo', 8, 3);
            $table->double('cantidad_segregado', 8, 3);
            $table->foreignId('sucursal_id')->nullable();
            $table->foreignId('bodega_id')->nullable();
            $table->foreignId('cliente_id')->nullable();
            $table->foreignId('usuario_creacion_id')->nullable();
            $table->foreignId('usuario_actualizacion_id')->nullable();
            $table->foreign('rol_logistica_detalle_id')->references('id')->on('roles_logisticas_detalle');
            $table->foreign('cliente_id')->references('id')->on('cat_clientes');
            $table->foreign('sucursal_id')->references('id')->on('cat_sucursales');
            $table->foreign('bodega_id')->references('id')->on('cat_bodegas');
            $table->foreign('usuario_creacion_id')->references('id')->on('users');
            $table->foreign('usuario_actualizacion_id')->references('id')->on('users');
            $table->foreignId('usuario_validacion_id')->nullable();
            $table->foreign('usuario_validacion_id')->references('id')->on('users')->nullable();
            $table->text('observaciones_residuo')->nullable();
            $table->char('validacion_correcta',2)->default('NO')->nullable();
            $table->char('inventario_afectado',2)->default('NO')->nullable();
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
        Schema::dropIfExists('residuos_segregaciones');
    }
}
