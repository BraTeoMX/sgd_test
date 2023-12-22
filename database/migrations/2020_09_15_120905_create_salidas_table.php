<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id');
            $table->string('folio_intimark', 50);
            $table->foreignId('usuario_creacion_id');
            $table->foreignId('usuario_modificacion_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('cat_clientes');
            $table->foreign('usuario_creacion_id')->references('id')->on('users');
            $table->foreign('usuario_modificacion_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salidas');
    }
}
