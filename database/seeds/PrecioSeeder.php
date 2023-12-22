<?php

use Illuminate\Database\Seeder;
use App\Precio;

class PrecioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Precio::create([
            'material_id'=>'1',
            'precio_general'=>'1.75',
            'precio_preferente'=>'1.65',
            'cliente_id'=>'1'
        ]);
    }
}
