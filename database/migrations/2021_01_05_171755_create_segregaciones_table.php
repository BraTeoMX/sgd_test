<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegregacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segregaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_logistica_detalle_id');
            $table->string('folio_segregacion', 50)->nullable();
            $table->double('peso_tara_segregacion', 8, 2)->nullable();
            $table->double('peso_bruto_segregacion', 8, 2)->nullable();
            $table->double('total_segregacion', 8, 3);
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
            $table->text('observaciones_segregacion')->nullable();
            $table->char('validacion_correcta',2)->default('NO')->nullable();
            $table->char('segregacion_correcta',2)->default('NO')->nullable();
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
        Schema::dropIfExists('segregaciones');
    }
}
