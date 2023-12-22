<?php

use Illuminate\Database\Seeder;
use App\RolLogistica;

class RolLogisticaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolLogistica::create([
            'folio_intimark'=>'1',
            'convenio_id'=>'1',
            // 'convenio_detalle_id'=>'1',           
            'fecha'=>'2020-09-20',
            'precio_combustible'=>'11.52',           
            'chofer_id'=>'1',
            'litros_reserva'=>'20',           
            'litros_cargar'=>'10',           
            //'kilometraje_recoleccion'=>'200',           
            // 'sucursal_id'=>'1',       
            // 'bodega_id'=>'1',           
            // 'cliente_destino_id'=>'1'
        ]);
    }
}
