<?php

namespace App\Http\Controllers;

use App\Parametros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Laracasts\Flash\Flash;
use JsValidator;
use File;


class ParametrosController extends Controller
{
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(Parametros $model)
    {
     $this->validationRules = [
            'id'=>'required',
            'parametro'=>'required',
            'valor'=>'required',
            'descripcion'=>'required',
            'modulo'=>'required'
            
       ];
       $this->attributeNames = [
            'id'=>'id',
            'parametro'=>'parametro',
            'valor'=>'valor',
            'descrpcion'=>'descripcion',
            'modulo'=>'modulo',
            
       ];
       $this->errorMessages = [
           'required' => 'El campo :attribute es requerido',
       ];
      $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parametros = $this->setQuery($request)->orderby('parametro')->get();
        return view('parametros.index', compact('parametros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parametro = new Parametros();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('parametros.form', compact('parametro', 'validator'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       // $this->setValidator($request)->validate();
       
        if ($this->setUnique($request)>0){
            $this->setMessage('Parametro duplicado');
        }else{
            $request = $this->normalizaDatos($request);
            $request['status'] = 'A';
            if($request->modulo==1){
                $modulo ='vac';
            }
            $request['clave'] = substr($request->parametro,0,3).'_'.$modulo;
            $nuevoparametro=Parametros::create($request->all());
                
            $this->setMessage('Parametro registrado exitosamente');
        }
        return redirect('/parametros');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Parametros 
     * @return \Illuminate\Http\Response
     */
    public function show(Parametros $parametro)
    {
        return view('parametros.view', compact('parametro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Parametros
     * @return \Illuminate\Http\Response
     */
    public function edit(Parametros $parametro)
    {      

       $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('parametros.form', compact('parametro', 'validator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Parametros
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parametros $parametro)
    {

       
       // $this->setValidator($request,$parametro->id)->validate();
      //dd($request->all());
        $request = $this->normalizaDatos($request);
      
        $parametro->update($request->all());
              
        $this->setMessage('parametro modificado exitosamente');
        return redirect('/parametros');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Parametros
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parametros $parametro)
    {
    
        $parametro->delete();
        $this->setMessage('parametro eliminado exitosamente');
        return redirect('/parametros');
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

    protected function setValidator(Request $request)
    {
        return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
    }

    private function setQuery($request)
    {
        
        $query = $this->model;
        if ($request->filled('parametros')) {
            $query = $query->where('parametro', 'LIKE', '%' . $request->input('parametros') . '%');
       }
        return $query;
    }

    //retorno de mensajes en el proyecto
    private function setMessage($mensaje)
    {
        return  flash($mensaje)->success()->important();
    }

    private function setUnique(Request $request){
        return Parametros::where('parametro',$request['parametro'])->count();
    }
}
