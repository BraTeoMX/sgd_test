<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesCompradoresDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_compradores_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_comprador_id', 50);
            $table->foreignId('material_id');
            $table->foreignId('inventario_id')->nullable();
            $table->float('total_material');
            $table->foreignId('unidad_medida_id');
            $table->foreignId('usuario_creacion_id')->nullable();
            $table->foreignId('usuario_actualizacion_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('material_id')->references('id')->on('cat_materiales');
            $table->foreign('inventario_id')->references('id')->on('inventarios');
            $table->foreign('unidad_medida_id')->references('id')->on('cat_unidades_medida');
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
        Schema::dropIfExists('roles_compradores_detalles');
    }
}
