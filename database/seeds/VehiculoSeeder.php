<?php

use Illuminate\Database\Seeder;
use App\Vehiculo;


class VehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vehiculo::create([
            'modelo'=>'X2',
            'marca'=>'BMW',
            'placas'=>'ERG123',
            'color'=>'rojo',
            'tipo_unidad'=>'automovil'
        ]);
    }
}
