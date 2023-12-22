<?php

use Illuminate\Database\Seeder;
use App\RolComprador;


class RolCompradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolComprador::create([
            'cliente_id'=>'1', 
            'sucursal_id'=>'1',   
            'fecha'=>'2020-09-20'
        ]);
    }
}
