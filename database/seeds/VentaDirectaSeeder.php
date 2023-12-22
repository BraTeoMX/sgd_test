<?php

use Illuminate\Database\Seeder;
use App\VentaDirecta;

class VentaDirectaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VentaDirecta::create([
            'rol_logistica_id'=>'1',
            'folio_comprador'=>'FolC01',
            'peso_bruto'=>'12970',
            'peso_tara'=>'11220',
            'peso_neto'=>'1750',
            'descuento'=>'350'
        ]);
    }
}
