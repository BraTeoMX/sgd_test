<?php

namespace App\Http\Controllers;

use App\DocumentoPermisos;
use App\FormatoPermisos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage ;
use JsValidator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class DocumentoPermisosController extends Controller
{

    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(DocumentoPermisos $model)
    {

        $this->validationRules = [
            'name' => 'bail|required|unique:permissions,name|max:40|string',
        ];
        $this->attributeNames = [
            'id'=>'id',
            'fecha_calendario'=>'fecha_calendario',
            'id_modulo'=>'id_modulo',
            'tipo'=>'tipo',
            'estatus_Fecha'=>'estatus_Fecha',
            'detalle'=>'detalle',
        ];
        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',
            'max'=>'El :attribute no debe exceder de 30 caracteres.',
            'alpha'=>'El :attribute no debe contener numeros o espacios.',
            'unique'=>'El :attribute ya se encuentra registrado.'
        ];
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $documentos = new DocumentoPermisos();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('documentopermisos.index', compact('documentos','validator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $inicio =date("Y-m-d");
        $fin =date("Y-m-d");

        $folio= $request->no_aux;

        $file = $request->file('file');
        $filename= $file->getClientOriginalName();
            $foo = \File::extension($filename);

            if ($foo == 'pdf'){

                 $file->move(public_path().'/Documentos/',$filename);
        }

        FormatoPermisos::WHERE('folio_per',$folio)->UPDATE(['documento'=>$filename]);

        $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
        ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
        ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
        ->select
        ('folio_per','tipo_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','documento')
      //  ->where('fech_ini_per', '=', $inicio)
        ->orderby('folio_per','desc')
        ->get();

        return view('estatuspermisos.index', compact('permisos','inicio','fin'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    private function setMessage($mensaje){

        return  flash($mensaje)->success()->important();

    }
}
