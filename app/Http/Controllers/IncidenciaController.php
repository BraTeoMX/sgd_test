<?php

namespace App\Http\Controllers;

use App\incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use JsValidator;


class incidenciaController extends Controller
{

    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(incidencia $model)
    {
     $this->validationRules = [
                                  'name' => 'bail|required|unique:permissions,name|max:40|string',
                            ];
       $this->attributeNames = [
                                     'name' => 'nombre del incidencia',
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
        $incidencias = $this->setQuery($request)->orderby('name')->get();
        return view('incidencias.index', compact('incidencias'));
    }

    public function create()
    {
        $incidencia = new incidencia();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('incidencias.form', compact('incidencia', 'validator'));
    }

    public function store(Request $request)
    {
        $this->setValidator($request)->validate();
        $incidencia = incidencia::create($request->all());
        flash('El registro ha sido agregado')->important();
        return redirect('/incidencia');
    }

    public function show($id)
    {
        //
    }

    public function edit($incidencia)
    {
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('incidencias.form', compact('incidencia', 'validator'));
    }

    public function update(Request $request, $incidencia)
    {
        $this->setValidator($request)->validate();
        $incidencia->update($request->all());
        flash('El registro ha sido actualizado')->important();
        return redirect('/incidencia');
    }

    public function destroy($incidencia)
    {
        $incidencia->delete();
        flash('El registro ha sido eliminado')->important();
        return redirect('/incidencia');
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
         $query = $query->orderBy('name','ASC');//where('trazabilidad_id', '=', auth()->user()->id;);
        if ($request->has('incidencia')) {
             $query = $query->where('name', 'LIKE', '%' . $request->input('incidencia') . '%');
        }
        return $query;
    }
}
