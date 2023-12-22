<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHoraLlegadaToRolesCompradores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_compradores', function (Blueprint $table) {
            $table->time('hora_llegada')->after('fecha')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles_compradores', function (Blueprint $table) {
            $table->dropColumn(['hora_llegada']);
        });
    }
}
