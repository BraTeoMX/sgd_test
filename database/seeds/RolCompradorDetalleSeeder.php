<?php

use Illuminate\Database\Seeder;
use App\RolCompradorDetalle;

class RolCompradorDetalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolCompradorDetalle::create([
            'rol_comprador_id'=>'1',    
            'material_id'=>'1',
            'inventario_id'=>'1',
            'total_material'=>'15',
            'unidad_medida_id'=>'1'
        ]);
    }
}
