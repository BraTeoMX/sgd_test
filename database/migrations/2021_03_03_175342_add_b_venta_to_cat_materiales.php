<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBVentaToCatMateriales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cat_materiales', function (Blueprint $table) {
            $table->unsigneInteger('venta')->after('destruccion')->nullable();
            $table->unsignedInteger('disposicion_final')->after('venta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cat_materiales', function (Blueprint $table) {
            $table->dropColumn(['destruccion']);
            $table->dropColumn(['disposicion_final']);
        });
    }
}
