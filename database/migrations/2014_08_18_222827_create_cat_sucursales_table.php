<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('clave',100)->unique();
            $table->string('sucursal');
            $table->string('codigo_postal',5);
            $table->string('estado_id');
            $table->string('municipio_id');
            $table->string('colonia_id');
            $table->string('calle',100);
            $table->string('exterior',20)->nullable();
            $table->string('interior',20);
            $table->string('telefono_principal',20);
            $table->string('telefono_secundario',20)->nullable();
            $table->unsignedBigInteger('encargado_id')->nullable();
            $table->unsignedBigInteger('usuario_creacion_id')->nullable();
            $table->unsignedBigInteger('usuario_actualizacion_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            /*$table->foreign('estado_id')->references('id')->on('cat_estados');
            $table->foreign('municipio_id')->references('id')->on('cat_municipios');
            $table->foreign('colonia_id')->references('id')->on('cat_localidades');
            $table->foreign('encargado_id')->references('id')->on('users');
            $table->foreign('usuario_creacion_id')->references('id')->on('users');
            $table->foreign('usuario_actualizacion_id')->references('id')->on('users');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_sucursales');
    }
}
