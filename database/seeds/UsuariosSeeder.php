<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'Amnhed Lagunas Hernandez',
            'email'=>'aalhe0.0@gmail.com',
            'puesto' => 'Administrador',
            'sucursal_id'=>null,
            'password'=>bcrypt('prueba'),
        ])->assignRole('Administrador');

        User::create([
            'name'=>'Nohemy Rodriguez Lara',
            'email'=>'nohemy.lara@hotmail.com',
            'puesto' => 'Administrador',
            'sucursal_id'=>null,
            'password'=>bcrypt('prueba'),
        ])->assignRole('Administrador');

        User::create([
            'name'=>'Víctor Clementes Santander',
            'email'=>'lia.victor.clementes@outlook.com',
            'puesto' => 'Administrador',
            'sucursal_id'=>null,
            'password'=>bcrypt('prueba'),
        ])->assignRole('Administrador');

        User::create([
            'name'=>'María del Carmen Figueroa Valois',
            'email'=>'gerenciafinanzas@intimarkmx.com',
            'puesto' => 'Gerente Finanzas',
            'sucursal_id'=>null,
            'password'=>bcrypt('prueba'),
        ])->assignRole('Gerencia Finanzas');
    }
}
