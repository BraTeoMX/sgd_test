<?php

use Illuminate\Database\Seeder;
use App\Bodega;

class BodegaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bodega::create([
            'sucursal_id'=>'1',
            'bodega'=>'Bodega Toluca',
            'clave'=>'BTOL01',
            'encargado_id'=>'1'

        ]);
    }
}
