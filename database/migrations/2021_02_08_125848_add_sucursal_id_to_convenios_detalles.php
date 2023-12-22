<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSucursalIdToConveniosDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convenios_detalles', function (Blueprint $table) {
            $table->unsignedBigInteger('sucursal_id')->after('observaciones')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convenios_detalles', function (Blueprint $table) {
            $table->dropColumn('sucursal_id');
        });
    }
}
