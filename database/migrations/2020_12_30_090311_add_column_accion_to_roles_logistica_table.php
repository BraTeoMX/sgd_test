<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAccionToRolesLogisticaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_logistica', function (Blueprint $table) {
            $table->string('accion', 50)->nullable()->after('litros_cargar');
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
            $table->dropColumn('accion');
        });
    }
}
