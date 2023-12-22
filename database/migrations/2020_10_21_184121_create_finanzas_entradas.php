<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanzasEntradas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finanzas_entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrada_detalle_id', 50);
            $table->foreignId('logistica_detalle_id', 50);
            $table->decimal('validacion_peso_bruto', 11, 2);
            $table->decimal('validacion_peso_tara', 11, 2);
            $table->decimal('validacion_total', 11, 2);
            $table->dateTime('fecha_validacion', 0)->nullable();
            $table->boolean('enviar_inventario')->default(0);
            $table->foreignId('usuario_creacion_id');
            $table->foreignId('usuario_modificacion_id')->nullable();
            //$table->foreign('entrada_detalle_id')->references('id')->on('entradas_detalles');
            $table->foreign('logistica_detalle_id')->references('id')->on('roles_logisticas_detalle');
            $table->foreign('usuario_creacion_id')->references('id')->on('users');
            $table->foreign('usuario_modificacion_id')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finanzas_entradas');
    }
}
