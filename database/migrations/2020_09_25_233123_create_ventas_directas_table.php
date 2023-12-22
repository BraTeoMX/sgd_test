<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasDirectasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_directas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rol_logistica_detalle_id');
            $table->string('folio_comprador',100);
            $table->float('peso_bruto');
            $table->float('peso_tara');
            $table->float('peso_neto');
            $table->float('descuento')->nullable();
            $table->unsignedBigInteger('usuario_creacion_id')->nullable();
            $table->unsignedBigInteger('usuario_actualizacion_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('ventas_directas');
    }
}
