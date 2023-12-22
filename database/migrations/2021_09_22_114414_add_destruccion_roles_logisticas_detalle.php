<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDestruccionRolesLogisticasDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_logisticas_detalle', function (Blueprint $table) {
            $table->decimal('cantidad_destruccion')->default('0.000');
            $table->string('destruccion_terminada')->nullable();
            $table->unsignedInteger('usuario_destruccion')->nullable();
            $table->datetime('fecha_destruccion')->nullable();
            $table->unsignedInteger('usuario_validacion_destruccion')->nullable();
            $table->datetime('fecha_validacion_destruccion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles_logistica', function (Blueprint $table) {
            $table->dropColumn(['cantidad_destruccion']);
            $table->dropColumn(['destruccion_terminada']);
            $table->dropColumn(['usuario_destruccion']);
            $table->dropColumn(['fecha_destruccion']);
            $table->dropColumn(['usuario_validacion_destruccion']);
            $table->dropColumn(['fecha_validacion_destruccion']);
        });
    }
}
