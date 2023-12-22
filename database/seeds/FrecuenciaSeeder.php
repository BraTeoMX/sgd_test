<?php

use Illuminate\Database\Seeder;
use App\Frecuencia;

class FrecuenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Frecuencia::create(
            [
                'frecuencia'=>'Por Llamada'     
            ],
            [
                'frecuencia'=>'Lunes a Viernes'     
            ],
            [
                'frecuencia'=>'1 vez por mes'     
            ]
        );
    }
}
