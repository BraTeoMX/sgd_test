<?php

namespace App\Http\Controllers;

use App\RolLogisticaDetalle;
use App\RolLogistica;
use App\UploadRecoleccion;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(UploadRecoleccion $model)
    {
        $this->validationRules = [
            'descripcion'=>'required|string|max:50'
        ];
        $this->attributeNames = [
            'descripcion'=>'Descripcion'
        ];
        $this->errorMessages = [
            'required' => 'El campo :attribute es requerido',
        ];
        $this->model = $model;
    }
    public function index(Request $request)
    {
        //$salidas = $this->setQuery($request)->get();
        $entradas = $this->setQuery($request)->get();
        $roleslogistica = RolLogistica::find(session('rollogistica_id'));
        return view('upload.index', compact('entradas','roleslogistica'));
    }


    public function create()
    {
        return view('upload.form');
        $uploadRecoleccion = new UploadRecoleccion();
        $roleslogistica = RolLogistica::find(session('rollogistica_id'));
        $conveniosdetalle = ConvenioDetalle::where('convenio_id', $roleslogistica->cliente_id)->get();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('upload.form', compact('uploadRecoleccion','roleslogistica','conveniosdetalle', 'validator'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        
        $uploadRecoleccion = new UploadRecoleccion();
        // dd($uploadRecoleccion);
        $fileName = time().'.'.$request->file('custom-file-boxed')->extension();  
        //dd($fileName);
        //$request->file->move(public_path('uploads'), $fileName);
        $request->file('custom-file-boxed')->storeAs('public',$fileName);
            $uploadRecoleccion->create([
                'roles_logistica_id' => session('rollogistica_id'),
                'descripcion' => $request->descripcion,
                'nombre_archivo' => $fileName
            ]);
            $this->setMessage('Se adjunto el archivo exitosamente');
        return redirect()->route('upload.index');
    }

    public function destroy(UploadRecoleccion $uploadrecoleccion)
    {
        $roleslogistica = RolLogistica::find(session('rollogistica_id'));
        $uploadrecoleccion->delete();
        $this->setMessage('Archivo adjunto eliminado exitosamente');
        return redirect()->route('upload.index', $roleslogistica);
    }

    private function setQuery(Request $request)
    {
        $query = $this->model;
        $query = $query->where('roles_logistica_id', session('rollogistica_id'));
        return $query;
    }

    //retorno de mensajes en el proyecto
    private function setMessage($mensaje)
    {
        return  flash($mensaje)->success()->important();
    }
}
