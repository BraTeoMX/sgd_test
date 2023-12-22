<?php

use Illuminate\Database\Seeder;
use App\Sucursal;

class SucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sucursal::create([
            'clave'=>'Suc1',
            'sucursal'=>'Toluca',
            'codigo_postal'=>'52149',
            'estado_id'=>'15',
            'municipio_id'=>'107',
            'colonia_id'=>'105',
            'calle'=>'Av. Independencia',
            'interior'=>'A',
            'telefono_principal'=>'7224178019',
            'telefono_secundario'=>'7224178020',
            'encargado_id'=>'1'
        ]);
        
    }
}
