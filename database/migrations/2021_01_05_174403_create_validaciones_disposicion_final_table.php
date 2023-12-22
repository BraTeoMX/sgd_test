<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValidacionesDisposicionFinalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validaciones_disposicion_final', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('disposicion_final_id')->nullable();
            $table->unsignedBigInteger('rol_logistica_detalle_id')->nullable();
            $table->unsignedBigInteger('validacion')->default(0);
            $table->dateTime('fecha_validacion', 0)->nullable();
            $table->string('observaciones', 255)->nullable();
            $table->unsignedBigInteger('usuario_creacion_id')->nullable();
            $table->unsignedBigInteger('usuario_actualizacion_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('disposicion_final_id')->references('id')->on('disposiciones_finales');
            $table->foreign('rol_logistica_detalle_id')->references('id')->on('roles_logisticas_detalle');
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
        Schema::dropIfExists('validaciones_disposiciones_finales');
    }
}
