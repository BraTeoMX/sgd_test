<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatosPlataToClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cat_clientes', function (Blueprint $table) {
            $table->string('equipo_planta',255)->after('observaciones')->nullable();
            $table->string('personal_planta',255)->after('equipo_planta')->nullable();
            $table->string('requerimientos',255)->after('personal_planta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cat_clientes', function (Blueprint $table) {
            $table->dropColumn(['equipo_planta']);
            $table->dropColumn(['personal_planta']);
            $table->dropColumn(['requerimientos']);
        });
    }
}
