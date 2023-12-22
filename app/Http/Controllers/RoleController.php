<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use JsValidator;


class RoleController extends Controller
{

    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(Role $model)
    {
     $this->validationRules = [
                                'name' => 'bail|required|unique:roles,name|max:30',
                              ];
       $this->attributeNames = [
                                 'name' => 'nombre del rol',
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
        $roles = $this->setQuery($request)->get();
        return view('role.index', compact('roles'));
    }

    public function create()
    {
        $rol = new Role();
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('role.form', compact('rol', 'validator'));
    }

    public function store(Request $request)
    {
        $this->setValidator($request)->validate();
        $request = $this->normalizaDatos($request);
        $rol = Role::create($request->all());
        flash('EL registro ha sido agregado')->important();
        return redirect('/role');
    }

    public function show($id)
    {
        //
    }

    public function edit(Role $rol)
    {
        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('role.form', compact('rol', 'validator'));
    }

    public function update(Request $request, Role $rol)
    {
        $this->setValidator($request)->validate();
        $rol->update($request->all());
        flash('El registro ha sido actualizado')->important();
        return redirect('/role');
    }

    public function destroy(Role $rol)
    {
        $rol->delete();
        flash('El registro ha sido eliminado')->important();
        return redirect('/role');
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

        if ($request->has('rol')) {
             $query = $query->where('name', 'LIKE', '%' . $request->input('rol') . '%');
        }
        return $query;
    }
}
