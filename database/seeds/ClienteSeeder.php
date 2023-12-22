<?php

use Illuminate\Database\Seeder;
use App\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cliente::create([
            'numero_cliente'=>'001-007',
            'razon_social'=>'Laboratorios Kener, S. A. de C. V. ',
            'nombre_comercial'=>'Laboratorios Kener, S. A. de C. V. ',
            'rfc'=>'LKE-600127-8F9',
            'telefono'=>'722-481-0900 ext. 2114',
            'email'=>'elizabeth.soto@kener.com.mx',
            'codigo_postal'=>'0200',
            'estado_id'=>'15',
            'municipio_id'=>'103',
            'colonia_id'=>'101',
            'calle'=>'Eje 1 Norte',
            'exterior'=>'Manzana C',
            'interior'=>'lote 3,4, 5',
            'planta'=>'1',
            'comprador'=>'1'
        ]);
    }
}
