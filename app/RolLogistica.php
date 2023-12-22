<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Illuminate\Support\Facades\DB;
/**
 * @property integer $id
 * @property integer $cliente_id
 * @property integer $material_id
 * @property integer $convenio_detalle_id
 * @property integer $chofer_id
 * @property integer $sucursal_id
 * @property integer $bodega_id
 * @property integer $usuario_creacion_id
 * @property integer $usuario_actualizacion_id
 * @property int $folio_intimark
 * @property string $sitio
 * @property string $fecha
 * @property float $precio_combustible
 * @property int $litros_reserva
 * @property int $litros_cargar
 * @property int $kilometraje_recoleccion
 * @property integer $cliente_destino_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property CatBodega $catBodega
 * @property User $user
 * @property CatCliente $catCliente
 * @property ConveniosDetalle $conveniosDetalle
 * @property CatMateriale $catMateriale
 * @property CatSucursale $catSucursale
 * @property User $user
 * @property User $user
 */
class RolLogistica extends Model
{
    use RoutesWithFakeIds, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles_logistica';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'convenio_id',
        'sitio_id',
        'sucursal_cliente_id',
        'chofer_id',
        'vehiculo_id',
        'usuario_creacion_id',
        'usuario_actualizacion_id',
        'folio_intimark',
        'fecha',
        'precio_combustible',
        'litros_solicitados',
        'monto_solicitado',
        'litros_reserva',
        'monto_reserva',
        'litros_cargar',
        'recolecion_terminada',
        'folio_seguridad',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'fecha',
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function catChofer()
    {
        return $this->belongsTo('App\Chofer', 'chofer_id')->withTrashed();
    }

    public function sitiosAll()
    {
        return $this->belongsTo('App\ClienteSitio', 'sitio_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehiculo()
    {
        return $this->belongsTo('App\Vehiculo', 'vehiculo_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function convenio()
    {
        return $this->belongsTo('App\Convenio', 'convenio_id')->withTrashed();
    }
    public function convenioAll()
    {
        return $this->belongsTo('App\Convenio', 'convenio_id')->withTrashed();
    }

    public function catCliente()
    {
        return $this->belongsTo('App\Cliente', 'cliente_id');
    }

    public function rolLogisticaDetalles()
    {
        return $this->hasMany('App\RolLogisticaDetalle', 'roles_logistica_id');
    }

    public function sucursalCliente()
    {
        return $this->belongsTo('App\Sucursal', 'sucursal_cliente_id')->withTrashed();
    }

    public function convenioCliente()
    {
        return $this->hasMany('App\Convenio', 'convenio_id');
    }

    public function getarregloClienteAttribute(){
        $clientes = Cliente::select('nombre_comercial')
        ->addSelect(['id'=> Convenio::select('id') 
        ->whereColumn('cat_clientes.id','cliente_id')
        ->whereIn('sucursal_id', function($query) {
            $query->select('sucursal_id')->from('users_sucursales')->where('usuario_id',auth()->user()->id);
        })
        ->limit(1) ])
        ->where('planta',1)
        ->get()->pluck('nombre_comercial', 'id')->prepend('-Selecciona-', '');

        // $clientes = Convenio::addSelect(['nombre_comercial' => Cliente::select('nombre_comercial')
        // ->whereColumn('cliente_id', 'cat_clientes.id')
        // ->limit(1)
        // ])->get()->pluck('nombre_comercial', 'id')->prepend('-Selecciona-', '');
        return $clientes;
    }

    public function getarregloChoferAttribute(){
        $choferes = Chofer::get()->pluck('nombre', 'id')->prepend('-Selecciona-', '');
        return $choferes;
    }

    public function getarregloClienteDestinoAttribute(){
        $clientes = Cliente::where('comprador', '1')->get()->pluck('nombre_comercial', 'id');
        return $clientes;
    }

    public function getarregloSucursalAttribute(){
        $sucursales = Sucursal::get()->pluck('sucursal', 'id');
        return $sucursales;
    }

    public function getarregloBodegaAttribute(){
        $bodegas = Bodega::get()->pluck('bodega', 'id');
        return $bodegas;
    }

    public function getarregloMaterialAttribute(){
        if(!empty($this->convenio_detalle_id)){
            $materiales = ConvenioDetalle::where('id',$this->convenio_detalle_id)
            ->with('catMateriales')->get()->pluck('catMateriales.material', 'id');
            return $materiales;
        }else
        return $materiales=['Seleccione el cliente'];

    }

    public function getarregloVehiculoAttribute()
    {
        return Vehiculo::all()->pluck('placas', 'id')->prepend('-Selecciona-', '');
    }

    public function getarregloSitioAttribute(){
        if(!empty($this->sitio_id)){
        $sitios = ClienteSitio::find($this->sitio_id)->get()->pluck('sitio', 'id');
        }else
        $sitios=['Seleccione el cliente'];

        return $sitios;
    }

    public function getarregloSucursalClienteAttribute(){
        if(!empty($this->sucursal_cliente_id)){
        $sucursalesCliente = Sucursal::find($this->sucursal_cliente_id)->get()->pluck('sucursal', 'id');
        }else{
        $sucursalesCliente=['Seleccione el cliente'];
        }
        return $sucursalesCliente;
    }

    public function consultaEntrada($material,$fecha,$sucursal){
        $entrada=RolLogistica::select('cat_materiales.id','cat_materiales.material',
        DB::raw("SUM(roles_logisticas_detalle.total_validacion) as entradas"))
         ->Join('roles_logisticas_detalle', 'roles_logisticas_detalle.roles_logistica_id', '=', 'roles_logistica.id')
         ->leftJoin('bodegas_compartidas','roles_logisticas_detalle.bodega_compartida_id','=','bodegas_compartidas.id')
         ->leftJoin('convenios_detalles','roles_logisticas_detalle.convenio_detalle_id','=','convenios_detalles.id')
         ->leftJoin('cat_materiales','convenios_detalles.material_id','=','cat_materiales.id')
         ->leftJoin('convenios','convenios_detalles.convenio_id','=','convenios.id')
         ->leftJoin('cat_clientes','convenios.cliente_id','=','cat_clientes.id')
         ->whereDate('fecha', '=', $fecha)
         ->whereNull('roles_logistica.deleted_at')
         ->where('bodegas_compartidas.sucursal_id',$sucursal)
         ->where('roles_logisticas_detalle.inventario_afectado','SI')
         ->where('convenios_detalles.material_id',$material)
         ->whereNull('roles_logisticas_detalle.cliente_id')
         ->groupBy('cat_materiales.id')
         ->get();
        return $entrada;
    }

    public function consultaSalida($material,$fecha,$sucursal){
        $salida=SalidaDetalle::select(DB::raw("SUM(salidas_detalles.intimark_peso_neto) as salidas"))
         ->whereDate('fecha', '=', $fecha)
         ->whereNull('deleted_at')
         ->where('sucursal_id',$sucursal)
         ->where('material_id',$material)
         ->groupBy('material_id')
         ->get();
         return $salida;
    }
}
