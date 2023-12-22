<?php

use Illuminate\Database\Seeder;
use App\EntradaDetalle;

class EntradaDetalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EntradaDetalle::create([
            'entrada_id' => '1',
            'folio_intimark' => 'BZ/0001/2020',
            'servicio_id' => '1',
            'material_id' => '1',
            'sucursal_id' => '1',
            'peso_cliente' => '500',
            'intimark_peso_bruto' => '500',
            'intimark_peso_tara' =>'500',
            'intimark_peso_neto' => '500',
            'tipo_servicio_id' => '1',
            'bodega_id' => '1',
            'destino_final' => 'Segregacion',
            'usuario_creacion_id' => '1',
            'usuario_modificacion_id' => null,
        ]);
    }
}
