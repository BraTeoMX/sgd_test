<?php

namespace App\Http\Controllers;

use App\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use JsValidator;


class PermisoController extends Controller
{

    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(Permiso $model)
    {
     $this->validationRules = [
                                  'name' => 'bail|required|unique:permissions,name|max:40|string',
                            ];
       $this->attributeNames = [
                                     'name' => 'nombre del permiso',
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
        $permisos = $this->setQuery($request)->get();
        return view('permisos.index', compact('permisos'));
    }

    public function create()
    {
        $permiso = new Permiso();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('permisos.form', compact('permiso', 'validator'));
    }

    public function store(Request $request)
    {
        $this->setValidator($request)->validate();
        $permiso = Permiso::create($request->all());
        flash('El registro ha sido agregado')->important();
        return redirect('/permiso');
    }

    public function show($id)
    {
        //
    }

    public function edit($permiso)
    {
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('permisos.form', compact('permiso', 'validator'));
    }

    public function update(Request $request, $permiso)
    {
        $this->setValidator($request)->validate();
        $permiso->update($request->all());
        flash('El registro ha sido actualizado')->important();
        return redirect('/permiso');
    }

    public function destroy($permiso)
    {
        $permiso->delete();
        flash('El registro ha sido eliminado')->important();
        return redirect('/permiso');
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
         $query = $query->orderBy('id','DESC');//where('trazabilidad_id', '=', auth()->user()->id;);
        if ($request->has('permiso')) {
             $query = $query->where('name', 'LIKE', '%' . $request->input('permiso') . '%');
        }
        return $query;
    }
}
