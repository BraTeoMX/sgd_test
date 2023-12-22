<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecoleccionPlantaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recoleccion_planta', function (Blueprint $table) {
            $table->id();
            $table->string('folio_planta', 50);
            $table->double('peso_tara_planta', 8, 2)->nullable();
            $table->double('peso_bruto_planta', 8, 2)->nullable();
            $table->double('total_piezas_peso', 8, 2);
            $table->dateTime('hora_llegada', 0);
            $table->dateTime('hora_salida', 0);
            $table->foreignId('rol_logistica_id');
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
        Schema::dropIfExists('recoleccion_planta');
    }
}
