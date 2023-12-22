<?php

namespace App\Http\Controllers;

use App\Calendario;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;
use JsValidator;

class CalendarioController extends Controller
{
    //
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(Calendario $model)
    {
        $this->validationRules = [
            'name' => 'bail|required|unique:permissions,name|max:40|string',
        ];
        $this->attributeNames = [
            'id'=>'id',
            'fecha_calendario'=>'fecha_calendario',
            'id_modulo'=>'id_modulo',
            'tipo'=>'tipo',
            'tipo_nomina'=>'tipo_nomina',
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
    

    public function index(Request $request){
        //echo 'hola desde el calendariooo';
        $fechas = $this->setQuery($request)->get();
        return view('calendario.index', compact('fechas'));
        //var_dump($fechas);
    }

    public function create(){
        //echo 'hola desde el editar';
        $calendario = new Calendario();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('calendario.form', compact('calendario', 'validator'));
        
    }

    public function store(Request $request)
    {
        $calendario = new Calendario ();
        $calendario->fecha_calendario = $request->fecha_calendario;
        $calendario->id_modulo = $request->modulo;
        $calendario->tipo = $request->tipo;
        $calendario->tipo_nomina = $request->tipo_nomina;
        $calendario->estatus_fecha = 1;
        $calendario->detalle = $request->detalle;
        //echo $calendario;
        $calendario->save();
        return redirect('/calendario');

    }

    public function edit(Calendario $calendario){
        #echo $parametro;
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('calendario.form', compact('calendario', 'validator'));

    }

    public function update(Request $request, Calendario $calendario){
        /*echo 'actualizar';
        echo $request;
        echo '#################################################';
        echo $calendario;*/
        $request = $this->normalizaDatos($request);
      
        $calendario->update($request->all());
        
        $this->setMessage('parametro modificado exitosamente');
        return redirect('/calendario');

    }

    public function destroy(Calendario $calendario){
        echo 'Eliminardo';
        $calendario->delete();
        $this->setMessage('parametro eliminado exitosamente');
        return redirect('/calendario');
        
    }

    private function setQuery($request)
    {
        $query = $this->model;
         $query = $query->orderBy('id','DESC');//where('trazabilidad_id', '=', auth()->user()->id;);
        if ($request->has('fecha_calendario')) {
             $query = $query->where('name', 'LIKE', '%' . $request->input('fecha_calendario') . '%');
        }
        return $query;
    }

    private function normalizaDatos(Request $request, $id=0)
    {
        if($id == 0){
            $request['usuario_creacion_id'] = auth()->user()->id;
        }else{
            $request['usuario_actualizacion_id'] = auth()->user()->id;
        }
        return $request;
    }

    private function setMessage($mensaje)
    {
        return  flash($mensaje)->success()->important();
    }

    private function setUnique(Request $request){
        return Calendario::where('fecha_calendario',$request['fecha_calendario'])->count();
    }

}
