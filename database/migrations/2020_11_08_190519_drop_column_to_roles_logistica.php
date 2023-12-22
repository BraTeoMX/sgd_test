<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnToRolesLogistica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_logistica', function (Blueprint $table) {
            $table->dropColumn('kilometraje_recoleccion');
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
            $table->integer('kilometraje_recoleccion')->after('litros_cargar');
        });
    }
}
