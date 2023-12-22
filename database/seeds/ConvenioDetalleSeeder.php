<?php

use App\ConvenioDetalle;
use Illuminate\Database\Seeder;

class ConvenioDetalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConvenioDetalle::create([
            'convenio_id'=>'1',
            'material_id'=>'1',
            'material_cliente'=>'Cartón del cliente',
            'unidad_medida_id'=>'1',           
            'precio_pagar'=>'.50',           
            'frecuencia_id'=>'1',           
        ]);
    }
}
