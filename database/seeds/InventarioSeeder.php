<?php

use Illuminate\Database\Seeder;
use App\Inventario;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Inventario::create([
            'material_id'=>'1',
            'sucursal_id'=>'1',
            'bodega_id'=>'1',
            'existencia_inicial'=>'50',
            'entradas'=>'10',
            'salidas'=>'5',
            'stock'=>'55',
            'usuario_creacion_id'=>'1',
            'usuario_modificacion_id'=>null,
        ]);
    }
}
