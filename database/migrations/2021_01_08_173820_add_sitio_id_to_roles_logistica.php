<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSitioIdToRolesLogistica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_logistica', function (Blueprint $table) {
            $table->foreignId('sitio_id')->nullable()->after('convenio_id');
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
            $table->dropColumn('sitio_id');
        });
    }
}
