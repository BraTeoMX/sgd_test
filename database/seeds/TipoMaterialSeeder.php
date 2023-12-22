<?php

use Illuminate\Database\Seeder;
use App\TipoMaterial;

class TipoMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoMaterial::create([
            'tipo_material'=>'Recuperables'
        ]);
        TipoMaterial::create([
            'tipo_material'=>'Servicios'
        ]);
    }
}
