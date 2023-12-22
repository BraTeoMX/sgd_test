<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSegregableDestruccionToCatMateriales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cat_materiales', function (Blueprint $table) {
            $table->unsignedBigInteger('unidad_medida_id')->after('clave')->nullable();
            $table->unsignedBigInteger('segregacion')->after('unidad_medida_id')->nullable();
            $table->unsignedBigInteger('destruccion')->after('segregacion')->nullable();
            $table->foreign('unidad_medida_id')->references('id')->on('cat_unidades_medida');

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
            $table->dropForeign(['unidad_medida_id']);
            $table->dropColumn(['unidad_medida_id']);
            $table->dropColumn(['segregacion']);
            $table->dropColumn(['destruccion']);
        });
    }
}
