<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompradorVerificacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprador_verificacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_comprador_id')->nullable();
            $table->float('peso_tara')->nullable();
            $table->boolean('asistio')->default(0);
            $table->foreignId('usuario_creacion_id')->nullable();
            $table->foreignId('usuario_actualizacion_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('rol_comprador_id')->references('id')->on('roles_compradores');
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
        Schema::dropIfExists('comprador_verificacion');
    }
}
