<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolLogisticaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_logistica', function (Blueprint $table) {
            $table->id();
            $table->string('folio_intimark', 50);
            $table->string('folio_seguridad', 50)->nullable();
            $table->char('recolecion_terminada', 2)->default('NO')->nullable();
            $table->foreignId('cliente_id');
            $table->string('sitio',100)->nullable();
            $table->date('fecha');
            $table->foreignId('chofer_id');
            $table->float('precio_combustible');
            $table->integer('litros_solicitados')->nullable();
            $table->float('monto_solicitado')->nullable();
            $table->foreignId('litros_reserva')->nullable();
            $table->float('monto_reserva')->nullable();
            $table->integer('litros_cargar');
            $table->integer('kilometraje_recoleccion');
            $table->foreignId('usuario_creacion_id')->nullable();
            $table->foreignId('usuario_actualizacion_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('cliente_id')->references('id')->on('convenios');
            $table->foreign('chofer_id')->references('id')->on('cat_choferes');
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
        Schema::dropIfExists('roles_logistica');
    }
}
