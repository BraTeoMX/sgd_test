<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatChoferesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_choferes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('telefono',20);
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('usuario_creacion_id')->nullable();
            $table->unsignedBigInteger('usuario_actualizacion_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('cat_choferes');
    }
}
