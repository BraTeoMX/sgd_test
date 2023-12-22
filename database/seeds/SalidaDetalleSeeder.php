<?php

use Illuminate\Database\Seeder;
use App\SalidaDetalle;

class SalidaDetalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalidaDetalle::create([
            'salida_id' => '1',
            'material_id' => '1',
            'intimark_peso_neto' => '300.5',
            'usuario_creacion_id' => '1'
        ]);
    }
}
