<?php

namespace App\Http\Controllers;

use App\Imports\SaldovacacionesImport;
use App\Saldovacaciones;
use App\Bitacora_cargasaldo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JsValidator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class SaldovacacionesController extends Controller
{

    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(Saldovacaciones $model)
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
        //
        //echo 'hola desde el index saldo vacaciones';
        //return view('parametros.index', compact('parametros'));
        $saldoVacaciones = new Saldovacaciones();
        $bitacora = Bitacora_cargasaldo::WHERE('fecha', '!=' ,'null')->orderBy('fecha','desc')->paginate(5);
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('saldovacaciones.index', compact('bitacora','validator'));
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
        $bitacora = new Bitacora_cargasaldo;
        $import = new SaldovacacionesImport;
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);
        #echo 'Paso la validacion----';
        $file = $request->file('file');
        $fecha = date("Y-m-d H:i:s");
        $bitacora->fecha = $fecha;
        $bitacora->archivo = $file->getClientOriginalName();

        Saldovacaciones::where('created_at','!=','null')->delete();
        if(Excel::import($import, $file)){
            $bitacora->no_registros = $import->getRowCount();
            $bitacora->save();
            DB::statement('Call actualizar_saldo_vac');
            $this->setMessage('Saldo de dias importados. Fecha: '.$fecha . '. Total: ' . $import->getRowCount() . ' registros');
        }else{

            $this->setMessage('Ocurrio un problema al actualizar los dias de vacaciones 2' );
        }
        $bitacora = Bitacora_cargasaldo::WHERE('fecha', '!=' ,'null')->orderBy('fecha','desc')->paginate(5);
        return view('saldovacaciones.index', compact('bitacora'));
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
