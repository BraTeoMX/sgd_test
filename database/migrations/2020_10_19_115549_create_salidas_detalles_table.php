<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalidasDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salidas_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_id');
            $table->foreignId('material_id');
            $table->decimal('intimark_peso_neto', 11, 2);
            $table->foreignId('usuario_creacion_id');
            $table->foreignId('usuario_modificacion_id')->nullable();
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
        Schema::dropIfExists('salidas_detalles');
    }
}
