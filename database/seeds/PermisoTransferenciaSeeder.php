<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisoTransferenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $controllers = ['transferencia'];

        $actions = ['index','create','store','edit','update','destroy','*'];

        foreach ($controllers as $controller) {
            foreach ($actions as $action) {
                Permission::create(['name' => $controller.'.'.$action, 'guard_name'=>'web']);
            }
        }

        //Rol administrador
        $administrador = Role::findByName('Administrador');
        $administrador->givePermissionTo(
            'transferencia.index',
            'transferencia.create',
            'transferencia.store',
            'transferencia.edit',
            'transferencia.update',
            'transferencia.destroy',
            'transferencia.*'
        );

        //Rol Dueño del Proceso
        $administrador = Role::findByName('Gerencia Finanzas');
        $administrador->givePermissionTo(
            'transferencia.index',
            'transferencia.create',
            'transferencia.store',
            'transferencia.edit',
            'transferencia.update',
            'transferencia.destroy',
            'transferencia.*'
        );

    }
}
