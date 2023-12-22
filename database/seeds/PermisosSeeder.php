<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisosSeeder extends Seeder
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

        $controllers = [
            'usuario','permiso','role','acceso','material','unidadmedida','vehiculo','sucursal','precio',
            'bodega','cliente','convenio','rollogistica','rollogisticadetalle','entrada','almacen','salida','comprador',
            'rolcomprador','rolcompradordetalle','conveniodetalle','ventadirecta','recoleccionplanta','upload','frecuencia',
            'chofer','clientesitio','cobro','finanzaentrada','clientematerial','conveniodetalleservicio','entradabodega',
            'razonsocial','formapago','validacionventadirecta','roldestruccion','rolsegregacion','disposicionfinal',
            'validaciondisposicionfinal','logisticaentrega','logisticaespecial','consultainventario',
            'consultacuentapagar','consultacuentacobrar','recoleccionrol','segregacion','conveniodetallesegregacion'
        ];

        $actions = ['index','create','store','edit','update','show','destroy'];

        foreach ($controllers as $controller) {
            foreach ($actions as $action) {
                Permission::create(['name' => $controller.'.'.$action]);
            }
        }

        Permission::create(['name' => 'home']);
    }
}
