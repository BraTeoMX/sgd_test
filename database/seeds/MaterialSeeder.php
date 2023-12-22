<?php

use Illuminate\Database\Seeder;
use App\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Material::create([
            'material'=>'Cartón',
            'tipo_material_id'=>'1',
            'clave'=>'123',
            'unidad_medida_id'=>'1'
        ]);
        Material::create([
            'material'=>'Basura',
            'tipo_material_id'=>'2',
            'clave'=>'456',
            'unidad_medida_id'=>'1'
        ]);
    }
}
