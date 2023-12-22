<?php

use Illuminate\Database\Seeder;
use App\Entrada;

class EntradaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Entrada::create([
            'cliente_id' => '1',
            'folio_cliente' => 'N/0001/2020',
            'usuario_creacion_id' => '1',
            'usuario_modificacion_id' => null,
        ]);
    }
}
