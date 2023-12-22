<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Rol administrador
        Role::create(['name'=>'Administrador'])->givePermissionTo(Permission::all());

        //Rol Gerencia Finanzas
        Role::create(['name'=>'Gerencia Finanzas'])->givePermissionTo(
                    'home',
                    'material.index','material.create','material.store','material.edit','material.update','material.destroy','material.show',
                    'unidadmedida.index','unidadmedida.create','unidadmedida.store','unidadmedida.edit','unidadmedida.update','unidadmedida.destroy','unidadmedida.show',
                    'vehiculo.index','vehiculo.create','vehiculo.store','vehiculo.edit','vehiculo.update','vehiculo.destroy','vehiculo.show',
                    'sucursal.index','sucursal.create','sucursal.store','sucursal.edit','sucursal.update','sucursal.destroy','sucursal.show',
                    'precio.index','precio.create','precio.store','precio.edit','precio.update','precio.destroy','precio.show',
                    'bodega.index','bodega.create','bodega.store','bodega.edit','bodega.update','bodega.destroy','bodega.show',
                    'cliente.index','cliente.create','cliente.store','cliente.edit','cliente.update','cliente.destroy','cliente.show',
                    'convenio.index','convenio.create','convenio.store','convenio.edit','convenio.update','convenio.destroy','convenio.show',
                    'conveniodetalle.index','conveniodetalle.create','conveniodetalle.store','conveniodetalle.edit','conveniodetalle.update','conveniodetalle.destroy','conveniodetalle.show',
                    'rollogistica.index','rollogistica.create','rollogistica.store','rollogistica.edit','rollogistica.update','rollogistica.destroy','rollogistica.show',
                    'rollogisticadetalle.index','rollogisticadetalle.create','rollogisticadetalle.store','rollogisticadetalle.edit','rollogisticadetalle.update','rollogisticadetalle.destroy','rollogisticadetalle.show',
                    'entrada.index','entrada.create','entrada.store','entrada.edit','entrada.update','entrada.destroy','entrada.show',
                    'ventadirecta.index','ventadirecta.create','ventadirecta.store','ventadirecta.edit','ventadirecta.update','ventadirecta.destroy','ventadirecta.show',
                    'salida.index','salida.create','salida.store','salida.edit','salida.update','salida.destroy','salida.show',
                    'almacen.index','almacen.create','almacen.store','almacen.edit','almacen.update','almacen.destroy','almacen.show',
                    'comprador.index','comprador.create','comprador.store','comprador.edit','comprador.update','comprador.destroy','comprador.show',
                    'rolcomprador.index','rolcomprador.create','rolcomprador.store','rolcomprador.edit','rolcomprador.update','rolcomprador.destroy','rolcomprador.show',
                    'rolcompradordetalle.index','rolcompradordetalle.create','rolcompradordetalle.store','rolcompradordetalle.edit','rolcompradordetalle.update','rolcompradordetalle.destroy','rolcompradordetalle.show',
                    'recoleccionplanta.index','recoleccionplanta.store','recoleccionplanta.edit','recoleccionplanta.update','recoleccionplanta.destroy','recoleccionplanta.show',
                    'razonsocial.index','razonsocial.store','razonsocial.edit','razonsocial.update','razonsocial.destroy','razonsocial.show',
                    'upload.index','upload.create','upload.store','upload.destroy','upload.show',
                    'frecuencia.index','frecuencia.create','frecuencia.store','frecuencia.edit','frecuencia.update','frecuencia.destroy','frecuencia.show',
                    'chofer.index','chofer.create','chofer.store','chofer.edit','chofer.update','chofer.destroy','chofer.show',
                    'clientesitio.index','clientesitio.create','clientesitio.store','clientesitio.edit','clientesitio.update','clientesitio.destroy','clientesitio.show',
                    'cobro.index','cobro.create','cobro.store','cobro.edit','cobro.update','cobro.destroy','cobro.show',
                    'finanzaentrada.index','finanzaentrada.create','finanzaentrada.store','finanzaentrada.edit','finanzaentrada.update','finanzaentrada.destroy','finanzaentrada.show',
                    'clientematerial.index','clientematerial.create','clientematerial.store','clientematerial.edit','clientematerial.update','clientematerial.destroy','clientematerial.show',
                    'conveniodetalleservicio.index','conveniodetalleservicio.create','conveniodetalleservicio.store','conveniodetalleservicio.edit','conveniodetalleservicio.update','conveniodetalleservicio.destroy','conveniodetalleservicio.show',
                    'formapago.index','formapago.create','formapago.store','formapago.edit','formapago.update','formapago.destroy','formapago.show',
                    'validacionventadirecta.index','validacionventadirecta.create','validacionventadirecta.store','validacionventadirecta.edit','validacionventadirecta.update','validacionventadirecta.destroy','validacionventadirecta.show',
                    'rolsegregacion.index','rolsegregacion.create','rolsegregacion.store','rolsegregacion.edit','rolsegregacion.update','rolsegregacion.destroy','rolsegregacion.show',
                    'roldestruccion.index','roldestruccion.create','roldestruccion.store','roldestruccion.edit','roldestruccion.update','roldestruccion.destroy','roldestruccion.show',
                    'disposicionfinal.index','disposicionfinal.create','disposicionfinal.store','disposicionfinal.edit','disposicionfinal.update','disposicionfinal.destroy','disposicionfinal.show',
                    'validaciondisposicionfinal.index','validaciondisposicionfinal.create','validaciondisposicionfinal.store','validaciondisposicionfinal.edit','validaciondisposicionfinal.update','validaciondisposicionfinal.destroy','validaciondisposicionfinal.show',
                    'logisticaentrega.index','logisticaentrega.create','logisticaentrega.store','logisticaentrega.edit','logisticaentrega.update','logisticaentrega.destroy','logisticaentrega.show',
                    'logisticaespecial.index','logisticaespecial.create','logisticaespecial.store','logisticaespecial.edit','logisticaespecial.update','logisticaespecial.destroy','logisticaespecial.show',
                    'consultainventario.index','consultacuentacobrar.index','consultacuentapagar.index',
                    'recoleccionrol.index','recoleccionrol.create','recoleccionrol.store','recoleccionrol.edit','recoleccionrol.update','recoleccionrol.destroy','recoleccionrol.show',
                    'segregacion.index','segregacion.create','segregacion.store','segregacion.edit','segregacion.update','segregacion.destroy','segregacion.show',
                    'conveniodetallesegregacion.index','conveniodetallesegregacion.create','conveniodetallesegregacion.store','conveniodetallesegregacion.edit','conveniodetallesegregacion.update','conveniodetallesegregacion.destroy','conveniodetallesegregacion.show',
                );

        Role::create(['name'=>'Logistica de Planta'])->givePermissionTo(
                                'home');

        Role::create(['name'=>'Supervisor de Planta'])->givePermissionTo(
                                'home',
                                'rollogistica.index','rollogistica.show',
                                'recoleccionplanta.index','recoleccionplanta.index','recoleccionplanta.store','recoleccionplanta.edit','recoleccionplanta.update','recoleccionplanta.destroy','recoleccionplanta.show',
                                'upload.index','upload.create','upload.store','upload.destroy','upload.show',                            );

        Role::create(['name'=>'Supervisor de Bodega'])->givePermissionTo(
                                'home');

        Role::create(['name'=>'Auxiliar de Operadores'])->givePermissionTo(
                                'home');

        Role::create(['name'=>'Auxiliar de Logistica'])->givePermissionTo(
                                'home',
                                'rollogistica.index','rollogistica.show',
                                'recoleccionplanta.index','recoleccionplanta.create','recoleccionplanta.store','recoleccionplanta.edit','recoleccionplanta.update','recoleccionplanta.show',
                                'upload.index','upload.create','upload.store','upload.destroy','upload.show',
                            );

        Role::create(['name'=>'Auxiliar de Finanzas'])->givePermissionTo(
                                'home');

        Role::create(['name'=>'Responsable de Bodega'])->givePermissionTo(
                    'entradabodega.index','entradabodega.create','entradabodega.store','entradabodega.edit','entradabodega.update','entradabodega.show',
                    'home');

        Role::create(['name'=>'Responsable de Sucursal'])->givePermissionTo(
                                'home');

        Role::create(['name'=>'Caja'])->givePermissionTo(
                                'home');

        Role::create(['name'=>'Coordinador de Ventas'])->givePermissionTo(
                                'home');

        Role::create(['name'=>'Recepcionista'])->givePermissionTo(
                                'home');

    }
}
