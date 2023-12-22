<?php

use Illuminate\Database\Seeder;
use App\Salida;

class SalidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Salida::create([
            'cliente_id' => 1,
            'folio_intimark' => 'SNK-0001-0002-0003-2020 ',
            'usuario_creacion_id' => 1,
            'usuario_modificacion_id' => null,
        ]);
    }
}
