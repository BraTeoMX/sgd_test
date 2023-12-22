<?php

namespace App\Http\Controllers;

use App\Puestos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Laracasts\Flash\Flash;
use JsValidator;
use File;


class PuestosController extends Controller
{
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(Puestos $model)
    {
     $this->validationRules = [
            'id'=>'required',
            'Puesto'=>'required',
            'Id_Planta'=>'required',
            'Nivel'=>'required'
            
       ];
       $this->attributeNames = [
            'id'=>'id',
            'Puesto'=>'Puesto',
            'Id_Planta'=>'Id_Planta',
            'Nivel'=>'Nivel'
            
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
        $puestos = $this->setQuery($request)->orderby('Puesto')->get();
        return view('Puestos.index', compact('puestos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $puesto = new Puestos();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('Puestos.form', compact('puesto', 'validator'));
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
       //dd($request->all());
        if ($this->setUnique($request)>0){
            $this->setMessage('puesto duplicado');
        }else{
            $request = $this->normalizaDatos($request);
            $request['Status'] = 'A';

            $nuevopuesto=Puestos::create($request->all());
                
            $this->setMessage('puesto registrado exitosamente');
        }
        return redirect('/puestos');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Puestos 
     * @return \Illuminate\Http\Response
     */
    public function show(Puestos $puesto)
    {
        return view('Puestos.view', compact('puesto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Puestos
     * @return \Illuminate\Http\Response
     */
    public function edit(Puestos $puesto)
    {      

       $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('Puestos.form', compact('puesto', 'validator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Puestos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Puestos $puesto)
    {

       
       // $this->setValidator($request,$puesto->id)->validate();
      //dd($request->all());
        $request = $this->normalizaDatos($request);
      
        $puesto->update($request->all());
              
        $this->setMessage('puesto modificado exitosamente');
        return redirect('/puestos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Puestos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Puestos $puesto)
    {
    
        $puesto->delete();
        $this->setMessage('puesto eliminado exitosamente');
        return redirect('/puestos');
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
        if ($request->filled('puesto')) {
            $query = $query->where('puesto', 'LIKE', '%' . $request->input('puesto') . '%');
       }
        return $query;
    }

    //retorno de mensajes en el proyecto
    private function setMessage($mensaje)
    {
        return  flash($mensaje)->success()->important();
    }

    private function setUnique(Request $request){
        return Puestos::where('puesto',$request['puesto'])->count();
    }
}
