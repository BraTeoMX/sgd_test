<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cliente',100);
            $table->string('nombre_comercial');
            $table->string('razon_social');
            $table->string('rfc',20)->nullable();
            //$table->string('contacto',100)->nullable();
            //$table->date('inicio_contrato')->nullable();
            //$table->date('fin_contrato')->nullable();
            $table->string('telefono',100)->nullable();
            $table->string('email',50)->nullable();
            $table->string('codigo_postal',5);
            $table->string('estado_id');
            $table->string('municipio_id');
            $table->string('colonia_id');
            $table->string('calle',100);
            $table->string('exterior',20)->nullable();
            $table->string('interior',20);
            $table->unsignedBigInteger('planta')->nullable();
            $table->unsignedBigInteger('comprador')->nullable();
            $table->unsignedBigInteger('otro_servicio')->nullable();
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('usuario_creacion_id')->nullable();
            $table->unsignedBigInteger('usuario_actualizacion_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            /*$table->foreign('estado_id')->references('id')->on('cat_estados');
            $table->foreign('municipio_id')->references('id')->on('cat_municipios');
            $table->foreign('colonia_id')->references('id')->on('cat_localidades');*/
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
        Schema::dropIfExists('cat_clientes');
    }
}
