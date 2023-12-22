<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrecioSegregacionToConveniosDetallesSegregables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convenios_detalles_segregables', function (Blueprint $table) {
            $table->unsignedInteger('precio_segregacion')->after('material_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convenios_detalles_segregables', function (Blueprint $table) {
            $table->dropColumn(['precio_segregacion']);
        });
    }
}
