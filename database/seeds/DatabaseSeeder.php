<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables([
            'users',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'permissions',
            'roles',
            'cat_choferes',
            'cat_sucursales',
            'cat_bodegas',
            'clientes_sitios',
            'cat_clientes',
            'cat_frecuencias',
            'cat_tipo_materiales',
            'cat_materiales',
            'cat_unidades_medida',
            'cat_vehiculos',
            //'entradas',
            'salidas',
            'inventarios',
            'convenios',
            'convenios_detalles',
            'roles_logistica',
            'ventas_directas',
            'roles_compradores_detalles',
            'roles_compradores',
          ]);

         $this->call([
             PermisosSeeder::class,
             RolesSeeder::class,
             UserSeeder::class,
             UsuariosSeeder::class,
             TipoMaterialSeeder::class,
             UnidadMedidaSeeder::class,
             MaterialSeeder::class,
             VehiculoSeeder::class,
             FrecuenciaSeeder::class,
             SucursalSeeder::class,
             ChoferSeeder::class,
             BodegaSeeder::class,
             ClienteSeeder::class,
             ClienteSitioSeeder::class,
             //EntradaSeeder::class,
             SalidaSeeder::class,
             //InventarioSeeder::class,
             ConvenioSeeder::class,
             //ConvenioDetalleSeeder::class,
             RolLogisticaSeeder::class,
             //VentaDirectaSeeder::class,
             RolCompradorSeeder::class,
             RolCompradorDetalleSeeder::class,
         ]);
    }
    public function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($tables as $table){
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
     }
}
