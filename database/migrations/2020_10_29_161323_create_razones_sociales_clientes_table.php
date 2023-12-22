<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRazonesSocialesClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('razones_sociales_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 150);
            $table->string('rfc', 18);
            $table->foreignId('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('cat_clientes');
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('usuario_creacion_id')->nullable();
            $table->foreignId('usuario_actualizacion_id')->nullable();
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
        Schema::dropIfExists('razones_sociales_clientes');
    }
}
