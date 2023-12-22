<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPesoBrutoToSalidasDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salidas_detalles', function (Blueprint $table) {
            $table->float('peso_bruto')->after('folio_intimark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salidas_detalles', function (Blueprint $table) {
            $table->dropColumn('peso_bruto');
        });
    }
}
