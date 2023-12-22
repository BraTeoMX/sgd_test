<?php

namespace App\Http\Controllers;

use App\Responsables;
use App\Puestos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use JsValidator;


class ResponsablesController extends Controller
{

    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(Responsables $model)
    {
     $this->validationRules = [
                                'name' => 'bail|required|unique:responsables,name|max:30',
                              ];
       $this->attributeNames = [
                                 'name' => 'nombre del responsable',
                               ];
       $this->errorMessages = [
                                'required' => 'El campo :attribute es obligatorio.',
                                'max'=>'El :attribute no debe exceder de 30 caracteres.',
                                'alpha'=>'El :attribute no debe contener numeros o espacios.',
                                'unique'=>'El :attribute ya se encuentra registrado.'
                             ];

      $this->model = $model;
    }

    public function index(Request $request)
    {
  
            $responsables = $this->setQuery($request)->get();
        
         return view('responsables.index', compact('responsables')); 
        
    }

    public function create()
    {
        $responsables = new Responsables();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        $puestos= Puestos::where('Nivel','0')->orderby('Puesto')->get();
        
        return view('responsables.form', compact('responsables', 'validator','puestos'));
    }

    public function store(Request $request)
    {
        $this->setValidator($request)->validate();
        $request = $this->normalizaDatos($request);

        $puesto = Puestos::where('id',$request->name)->get()->first();
        
        $request['guard_name'] = $puesto->Puesto;

        $responsables = Responsables::create($request->all());
        flash('EL registro ha sido agregado')->important();
        return redirect('/responsables');
    }

    public function show($id)
    {
        //
    }

    public function edit(Responsables $responsable)
    {
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('responsables.form', compact('responsable', 'validator'));
    }

    public function update(Request $request, Responsables $responsable)
    {
        $this->setValidator($request)->validate();
        $responsable->update($request->all());
        flash('El registro ha sido actualizado')->important();
        return redirect('/responsables');
    }

    public function destroy(Responsables $responsable)
    {
        $responsable->delete();
        flash('El registro ha sido eliminado')->important();
        return redirect('/responsables');
    }

    private function normalizaDatos(Request $request, $id=0)
    {
        return $request;
    }

    protected function setValidator(Request $request, $id=0)
    {
        return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
    }

    private function setQuery($request)
    {
        $query = $this->model;
       
        if ($request->has('responsable')) {
           
             $query = $query->where('guard_name', 'LIKE', '%' . $request->input('responsable') . '%');
        }
        return $query;
    }
}
