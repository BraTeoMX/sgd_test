<?php



namespace App\Http\Livewire;



use Livewire\Component;

use App\RolLogistica;

use App\RolLogisticaDetalle;

use App\Material;

use App\Bodega;

use App\TipoMaterial;

use App\Sucursal;

use App\Cliente;

use App\ClienteSitio;

use App\EstatusDetalle;



class FinanzaEntradaValidacion extends Component

{

    public $orderProducts = [];

    public $allMateriales = [];

    public $allBodegas = [];

    public $allSucursales = [];

    public $allClientes = [];

    public $allClienteSitios = [];

    public $allEstatus = [];



    protected $rules = [

        'name' => 'required',

        'email' => 'required|email',

        'comment' => 'required|min:5',

    ];



    public function mount()

    {

        $this->allMateriales = Material::all()->pluck('material','id');

        $this->allBodegas = Bodega::all()->pluck('bodega','id');

        $this->allSucursales = Sucursal::all()->pluck('sucursal','id');

        $this->allClientes = Cliente::where('comprador', '1')->get()->pluck('nombre_comercial','id');

        $this->allClienteSitios = ClienteSitio::all()->pluck('sitio','id');

        $this->allEstatus = EstatusDetalle::all()->pluck('estatus','id');

        $this->orderProducts = [

            [

                'id'=>'',

                'material_id'=>'',

                'sucursal_id'=>'',

                'sitio_id'=>'',

                'folio_planta'=>'',

                'peso_tara_planta'=>'',

                'peso_bruto_planta'=>'',

                'total_planta'=>'',

                'recoleccion_asitencia'=>'',

                'hora_llegada_planta'=>'',

                'hora_salida_planta'=>'',

                'peso_tara_bodega'=>'',

                'peso_bruto_bodega'=>'',

                'total_bodega'=>'',

                'hora_llegada_planta'=>'',

                'hora_salida_planta'=>'',

                'peso_tara_validacion'=>'',

                'peso_bruto_validacion'=>'',

                'total_validacion'=>'',

                'observaciones_validacion'=>'',

                'estatus_detalle_id'=>''

            ]

        ];



        if(filled(RolLogistica::where('fecha', session('fecha_validacion')))) {

            

            $this->orderProducts = RolLogisticaDetalle::select(

                'roles_logisticas_detalle.id',

                'roles_logisticas_detalle.roles_logistica_id',

                'roles_logisticas_detalle.bodega_id',

                'roles_logistica.folio_intimark', 

                'cat_clientes.nombre_comercial',

                'cat_materiales.material',

                'roles_logistica.fecha',

                'convenios_detalles.material_cliente',

                'cat_unidades_medida.unidad_medida',

                'convenios_detalles.precio_pagar',

                'roles_logisticas_detalle.material_id', 

                'roles_logisticas_detalle.sitio_id', 

                'roles_logistica.sucursal_cliente_id',

                'cat_sucursales.sucursal',

                'roles_logisticas_detalle.cliente_id',

                'folio_planta',

                'roles_logistica.folio_intimark',

                'peso_tara_planta',

                'peso_bruto_planta',

                'total_planta',

                'peso_tara_planta',

                'peso_bruto_planta',

                'total_planta',

                'peso_tara_bodega',

                'peso_bruto_bodega',

                'total_bodega',

                'hora_llegada_planta',

                'hora_salida_planta',

                'peso_tara_validacion',

                'peso_bruto_validacion',

                'total_validacion',

                'observaciones_validacion',

                'estatus_detalle_id'

                )

                ->join('roles_logistica', 'roles_logisticas_detalle.roles_logistica_id', '=', 'roles_logistica.id')

                ->join('cat_sucursales', 'roles_logistica.sucursal_cliente_id', '=', 'cat_sucursales.id')

                ->join('cat_materiales', 'roles_logisticas_detalle.material_id', '=', 'cat_materiales.id')

                ->join('convenios', 'roles_logistica.cliente_id', '=', 'convenios.id')

                ->join('convenios_detalles',function($join){

                    $join->on('convenios.id','=','convenios_detalles.convenio_id')

                    ->on('roles_logisticas_detalle.material_id','=','convenios_detalles.material_id');

                })

                ->join('cat_clientes', 'convenios.cliente_id', '=', 'cat_clientes.id')

                ->join('cat_unidades_medida', 'convenios_detalles.unidad_medida_id', '=', 'cat_unidades_medida.id')

                ->where('roles_logistica.fecha', session('fecha_validacion'))

                ->orderBy('roles_logistica.cliente_id')

                ->get()

                ->toArray();

        }



    }

    

    public function render()

    {

        return view('livewire.finanza-entrada-validacion');

    }

}

