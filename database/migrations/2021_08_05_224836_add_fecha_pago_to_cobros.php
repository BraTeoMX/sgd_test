<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaPagoToCobros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cobros', function (Blueprint $table) {
            $table->foreignId('rol_comprador_id')->nullable();
            $table->foreignId('sucursal_id')->nullable();
            $table->unsignedBigInteger('folio');
            $table->double('precio_material')->nullable();
            $table->foreignId('forma_pago_id')->nullable();
            $table->string('status_pago',20)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_pago')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cobros', function (Blueprint $table) {
            $table->dropColumn(['rol_comprador_id']);
            $table->dropColumn(['sucursal_id']);
            $table->dropColumn(['folio']);
            $table->dropColumn(['precio_material']);
            $table->dropColumn(['forma_pago_id']);
            $table->dropColumn(['status_pago']);
            $table->dropColumn(['fecha_vencimiento']);
            $table->dropColumn(['fecha_pago']);            
        });
    }
}
