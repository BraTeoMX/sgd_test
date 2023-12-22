<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterialRecuperadoToSegregaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('segregaciones', function (Blueprint $table) {
            $table->float('cantidad_material_recuperado')->after('folio_segregacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segregaciones', function (Blueprint $table) {
            $table->dropColumn('total_segregacion');

        });
    }
}
