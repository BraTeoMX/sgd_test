<?php

use Illuminate\Database\Seeder;
use App\ClienteSitio;

class ClienteSitioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClienteSitio::create([
            'cliente_id'=>'1',
            'sitio'=>'MAQUILADORA COECILLO',
            'codigo_postal'=>'50200',
            'estado_id'=>'15',
            'municipio_id'=>'107',
            'colonia_id'=>'100',
            'calle'=>'INDUSTRIA AUTOMOTRIZ',
            'interior'=>'520'
        ]);
    }
}
