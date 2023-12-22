<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Municipio;
use App\CodigoPostal;
use App\ConvenioDetalle;
use App\Convenio;
use App\ClienteSitio;
use App\Cliente;
use App\Tbl_Empleado_SIA;
use App\Localidad;
use App\ControlClienteSucursal;
use App\ChoferSucursal;
use App\CatalogoPermisos;
use JsValidator;
use Illuminate\Support\Facades\DB;


class ConsultasAjaxController extends Controller
{

    public function __construct()
    {
        $this->validationRules = [
            'codigo_postal' => 'required',
            'descripcion_municipio' => 'required',
        ];

        $this->attributeNames = [
            'codigo_postal' => 'Código postal',
            'descripcion_municipio' => 'Municipio',
        ];

        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio',
        ];
    }
    /**
    *@param _token string
    *@param cTipoConsulta string
    *@param cValor string
    *@return data HTML
    **/
    public function getcatalogos(Request $request)
    {
        if($request->ajax()){
            switch ($request->cTipoConsulta) {
                case 'municipio':
                    $municipios = Municipio::where('estado_id', $request->cValor)
                    ->orderBy('municipio', 'asc')
                    ->get()
                    ->toJson();
                    return $municipios;
                    break;

                default:

                    break;
            }
        }
    }

    public function getcodigospostales(Request $request)
    {
        $codigospostales = [];
        $strCodigoPostal = "";
        if ($request->post()) {

            $strCodigoPostal = $request->codigo_postal;
            $this->setValidator($request)->validate();

            $codigospostales = Localidad::where('codigo_postal', 'like', $request->codigo_postal.'%')
            ->with('estados')
            ->get();
        }

        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('codigospostales.form', compact('codigospostales', 'validator', 'strCodigoPostal'));
    }

    public function getmunicipios(Request $request)
    {

        $municipios = [];
        $strMunicipio = "";
        if ($request->post()) {
            $strMunicipio = $request->descripcion_municipio;
            $this->setValidator($request)->validate();
            $municipios = Municipio::where('municipio', 'like', $request->descripcion_municipio.'%')
            ->with('estados')
            ->get();
        }
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('municipios.form', compact('municipios', 'validator', 'strMunicipio'));
    }

    public function getmateriales($convenio_id)
    {
        $materiales = ConvenioDetalle::select('id','material_cliente')
                    ->where('convenio_id', '=', $convenio_id)
                    ->whereNull('convenios_detalles.deleted_at')
                    ->get();
        return response()->json($materiales);
    }

    public function getsitios($cliente_id)
    {
        $cliente = Convenio::select('cliente_id')
            ->where('id', '=', $cliente_id)
            ->whereNull('deleted_at')
            ->get();
        $sitios = ClienteSitio::select('id','sitio')
            ->where('cliente_id', '=', $cliente[0]->cliente_id)
            ->whereNull('deleted_at')
            ->get();
        return response()->json($sitios);
    }

    public function getconveniodetalle($convenio_id,$material_id)
    {
        $sitios = ClienteSitio::select('id','sitio')
                    ->where('cliente_id', '=', $cliente_id)
                    ->whereNull('deleted_at')
                    ->get();
        return response()->json($sitios);
    }

    public function getobservacioncliente($cliente_id)
    {
        $observaciones = Cliente::select('observaciones')
                    ->where('id', '=', $cliente_id)
                    ->whereNull('deleted_at')
                    ->get();
        return response()->json($observaciones);
    }

    public function getsucursalcliente($cliente_id)
    {
        $sucursales = ControlClienteSucursal::select('sucursal_id','sucursal')
                    ->join('cat_sucursales','cat_sucursales.id','ctrl_clientes_sucursales.sucursal_id')
                    ->where('ctrl_clientes_sucursales.cliente_id', '=', $cliente_id)
                    ->get();
        return response()->json($sucursales);
    }

    public function getsucursalclienteconvenio($convenio_id)
    {
        $convenio = Convenio::select('cliente_id')
        ->where('id', '=', $convenio_id)
        ->whereNull('deleted_at')
        ->get();
        $sucursales = ControlClienteSucursal::select('cat_sucursales.id','sucursal')
                    ->join('cat_sucursales','cat_sucursales.id','ctrl_clientes_sucursales.sucursal_id')
                    ->where('ctrl_clientes_sucursales.cliente_id', '=', $convenio[0]->cliente_id)
                    ->get();
        return response()->json($sucursales);
    }
//step rol obtiene clientes (convenios)
    public function getclientesbysucursales($sucursal_id)
    {
        $clientesconvenio = ControlClienteSucursal::select('convenios.id','cat_clientes.nombre_comercial')
                    ->join('cat_clientes','ctrl_clientes_sucursales.cliente_id','cat_clientes.id')
                    ->join('convenios','cat_clientes.id','convenios.cliente_id')
                    ->where('ctrl_clientes_sucursales.sucursal_id', '=', $sucursal_id)
                    ->where('convenios.inactivo', '=', '0')
                    ->get();
        return response()->json($clientesconvenio);
    }
//fin step rol

    public function getchoferbysucursal($sucursal_id)
    {
        $chofersucursal = ChoferSucursal::select('cat_choferes.id','cat_choferes.nombre')
                    ->join('cat_choferes','choferes_sucursales.chofer_id','cat_choferes.id')
                    ->where('choferes_sucursales.sucursal_id', '=', $sucursal_id)
                    ->get();
        return response()->json($chofersucursal);
    }

    /**
    *@param Request request
    *@param int id
    *@return Illuminate\Support\Facades\Validator
    **/
    protected function setValidator(Request $request, $id = 0) {

        if (!empty($request->codigo_postal)) {
            unset($this->validationRules['descripcion_municipio']);
            $this->validationRules = array_merge($this->validationRules, ['codigo_postal' => 'required|digits:5',]);
        }
        if (!empty($request->descripcion_municipio)) {
            unset($this->validationRules['codigo_postal']);
            $this->validationRules = array_merge($this->validationRules, ['descripcion_municipio' => 'required|min:4|alpha_num',]);
        }
        $validator = Validator::make($request->all(), $this->validationRules);
        $validator->setAttributeNames($this->attributeNames);
        return $validator;
    }

    public function addmaterial(Request $request)
    {
        dd($request);
        $codigospostales = [];
        $strCodigoPostal = "";
        if ($request->post()) {

            $strCodigoPostal = $request->codigo_postal;
            $this->setValidator($request)->validate();

            $codigospostales = Localidad::where('codigo_postal', 'like', $request->codigo_postal.'%')
            ->with('estados')
            ->get();
        }

        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('codigospostales.form', compact('codigospostales', 'validator', 'strCodigoPostal'));
    }

    public function getmotivopermisos($tipo)
    {

        $motivos = CatalogoPermisos::select('id_permiso','permiso')
                    ->where('forma', '=', $tipo)
                    ->get();
        return response()->json($motivos);
    }

    public function getautorizar($tipo)
    {

        $campos = explode("|",$tipo);

        $motivos = CatalogoPermisos::where('id_permiso', '=', $campos[0])
                    ->get();

       if ($motivos[0]->firma_vp==1 || $campos[1]==$campos[2]){

            $empleado = Tbl_Empleado_SIA::select('cveci2','Puesto')->where('Status_Emp','=','A')
            ->where('Tbl_Empleados_SIA.No_Empleado',$campos[1])
            ->get()->first();

            $autoriza = Tbl_Empleado_SIA::join('permiso_vac','Tbl_Empleados_SIA.Puesto','=','permiso_vac.id_jefe')->join('cat_puestos','Tbl_Empleados_SIA.Puesto','cat_puestos.id_puesto')
            ->where('Status_Emp','=','A')
            ->where('id_puesto_solicitante',$empleado->cveci2)
            ->where('id_jefe','<>',$empleado->Puesto)
            ->where('Nivel',1)
            ->orderby('Nom_Emp')->get();
         }else{
            $empleado = Tbl_Empleado_SIA::select('cveci2','Puesto','Modulo')->where('Status_Emp','=','A')
            ->where('Tbl_Empleados_SIA.No_Empleado',$campos[1])
            ->get()->first();

          //  if($empleado->Modulo!=''){
                $autoriza = Tbl_Empleado_SIA::where('Status_Emp','=','A')->where('No_Empleado',str_pad($campos[2], 7, "0", STR_PAD_LEFT))

                ->orderby('Nom_Emp')->get();
            /*}else{
                $autoriza = Tbl_Empleado_SIA::join('permiso_vac','Tbl_Empleados_SIA.Puesto','=','permiso_vac.id_jefe')->join('cat_puestos','Tbl_Empleados_SIA.Puesto','cat_puestos.id_puesto')
                ->where('Status_Emp','=','A')
                ->where('id_puesto_solicitante',$empleado->cveci2)
                ->where('id_jefe','<>',$empleado->Puesto)
                ->where('Nivel','<=',2)
                ->orderby('Nom_Emp')->get();
            }*/


         }

        return response()->json($autoriza);
    }
}
