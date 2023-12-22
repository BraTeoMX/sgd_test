<?php

use Illuminate\Database\Seeder;
use App\Convenio;

class ConvenioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Convenio::create([
            'cliente_id'=>'1',
            'contacto'=>'Elizabeth Soto',
            'inicio_contrato'=>'2018-02-01',
            'fin_contrato'=>'2019-02-01'           
        ]);
    }
}
