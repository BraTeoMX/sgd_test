<?php

use Illuminate\Database\Seeder;
use App\Chofer;

class ChoferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Chofer::create([
            'nombre'=>'Ernesto Perez Perez',
            'telefono'=>'7224178129',
            'sucursal_id'=>'1'
        ]);
    }
}
