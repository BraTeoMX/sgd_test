<?php

use Illuminate\Database\Seeder;
use App\UnidadMedida;

class UnidadMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnidadMedida::create([
            'clave'=>'KG',
            'unidad_medida'=>'Kilogramo'
        ]);
        UnidadMedida::create([
            'clave'=>'PZA',
            'unidad_medida'=>'Pieza'
        ]);
    }
}
