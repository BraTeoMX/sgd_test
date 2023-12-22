<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSerieToClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cat_clientes', function (Blueprint $table) {
            Schema::table('cat_clientes', function (Blueprint $table) {
                $table->string('serie',50)->after('requerimientos')->nullable();

            });
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
            $table->dropColumn(['serie']);
        });
    }
}
