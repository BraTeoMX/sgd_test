<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Laracasts\Flash\Flash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Sucursal;
use App\Bodega;
use App\Cliente;
use App\UsersBodega;
use App\UsersSucursal;
use App\UsersPlanta;
use App\Mail\RegistroUsuario;
use App\Rules\PrevenirEscaladoRol;
use JsValidator;

class UsuarioController extends Controller
{
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(User $model)
    {
        $this->validationRules = [
            'name' => 'required|string|between:5,190',
            'email' => 'required|email|max:100|string',
            'rol' => 'bail|required|array|max:40',
            'no_empleado' => 'bail|required|max:40',
            // 'sucursal_id' => 'sometimes|nullable|integer|exists:cat_sucursales,id',
            // 'bodega_id' => 'sometimes|nullable|integer|exists:cat_bodegas,id',
            'puesto' => 'required|string|between:5,190',
            'password' => 'sometimes|nullable|confirmed|between:9,50|string',
            'password_confirmation'=> 'sometimes|nullable|between:9,50|string',
        ];
       $this->attributeNames = [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'puesto' => 'puesto',
            'no_empleado' => 'no_empleado',
            // 'sucursal_id' => 'sucursal',
            // 'bodega_id' => 'bodega',
            'password' => 'contraseña',
            'password_confirmation'=> 'repetir contraseña',
       ];
       $this->errorMessages = [
           'required' => 'El campo :attribute es obligatorio.',
           'required_unless' => 'El campo :attribute es obligatorio cuando el rol no es Administrador o Gerencia Finanzas.',
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

        $usuarios = $this->setQuery($request)->get();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = new Role();
        if(auth()->user()->is_rol_superior) {
            $roles = $roles->where('id', '>', auth()->user()->roles()->first()->id);
        }
        $roles = $roles->orderBy('name', 'asc')->pluck('name','name');
        $usuario = new User();

        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');

        return view('usuarios.form', compact('usuario', 'validator','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $mail = User::WHERE('email',$request->email)->get()->count();
        $noemp = User::WHERE('no_empleado',$request->no_empleado)->get()->count();
        #echo $mail;
        if($mail != 1 && $noemp != 1){
            $this->setValidator($request)->validate();
            $request = $this->normalizaDatos($request);
            $usuario = User::create($request->all());
            $usuario->syncRoles($request->rol);
            Mail::to($usuario->email)->send(new RegistroUsuario($usuario));
            flash('El registro ha sido agregado')->important();
            return redirect('/usuario');
        }else{
            flash::error('Error: El correo y/o número de empleado ya está registrado con un usuario')->important();
            return redirect('/usuario');
        }


    }

    public function setpassword(User $usuario)
    {

        $validator = JsValidator::make([
            'password' => 'required|confirmed|min:9|max:50|string',
            'password_confirmation'=> 'required|min:9|max:50|string',
        ], $this->errorMessages, $this->attributeNames, '#form');

        return view('usuarios.setpassword', compact('usuario', 'validator'));
    }


    public function setpassword2(User $usuario)
    {

        $validator = JsValidator::make([
            'password' => 'required|confirmed|min:9|max:50|string',
            'password_confirmation'=> 'required|min:9|max:50|string',
        ], $this->errorMessages, $this->attributeNames, '#form');

        return view('nuevo', compact('usuario', 'validator'));
    }

    public function passwordupdate(Request $request)
    {

        $this->setValidator($request)->validate();
       // $request = $this->normalizaDatos($request);

        User::WHERE('no_empleado',$request->usuario)->UPDATE(['password' =>bcrypt($request->password)]);

        return redirect('/home');

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

    public function notificacion($usuario)
    {

        $usuario->update(['fecha_ultima_notificacion'=>now()]);
        Mail::to($usuario->email)
            ->send(new RegistroUsuario($usuario));
        flash('Correo electrónico enviado exitosamente')->important();
        return redirect('/usuario');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($usuario)
    {
        $roles = new Role();

        $roles = $roles->where('name','<>','Administrador')->orderBy('name', 'asc')->pluck('name','name');


        $validator = JsValidator::make($this->validationRules, $this->errorMessages, $this->attributeNames, '#form');
        return view('usuarios.form', compact('usuario', 'validator','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $usuario)
    {
        $this->setValidator($request)->validate();
        $request = $this->normalizaDatos($request);

        $usuario->update($request->all());


        flash('El registro ha sido actualizado')->important();
        if(isset($request->_setpassword) and $request->_setpassword == '1') {
            return redirect('/login');
        }
        $usuario->syncRoles($request->rol);
        return redirect('/usuario');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function normalizaDatos(Request $request,$id=0)
    {
       if(!isset($request->fecha_ultima_notificacion) || empty($request->fecha_ultima_notificacion)){
         $request['fecha_ultima_notificacion'] = date("Y-m-d");
       }
       if(!isset($request->inactivo)) {
           $request['inactivo'] = '0';
       }
        if(isset($request->password) && !empty($request->password)){
            $request['password'] = bcrypt($request->password);
        }



        return $request;
    }

    protected function setValidator(Request $request, $id=0)
    {

        if($id != 0){
            $this->validationRules = array_merge($this->validationRules, [
                'email' => 'required|email|unique:users,email,'.$id.',id|string|max:100'
            ]);
        }
        if(isset($request->_setpassword) and $request->_setpassword == '1'){
            $this->validationRules = [
                'password' => 'required|confirmed|min:9|max:50|string',
                'password_confirmation'=> 'required|min:9|max:50|string',
            ];
        }

        // if(filled($request->rol))
        // {
        //   if(!in_array('Administrador', $request->rol) && !in_array('Gerencia Finanzas', $request->rol))
        //   {
        //       $this->validationRules['sucursal_id'] = 'required|integer|exists:cat_sucursales,id';
        //   }
        // }

        return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
    }

    private function setQuery($request)
    {

        $role_id = auth()->user()->roles()->first()->id;
        $query = $this->model;

        // if($query->is_rol_superior) {
        //     $query=$query->whereHas('role', function ($query) use($role_id) {
        //         $query->where('id', '>', $role_id);
        //     });
        // }
        if ($request->filled('name')) {
             $query = $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }
       if($request->filled('email')) {
            $query = $query->where('email', 'LIKE', '%' . $request->input('email') . '%');
        }
        if($request->filled('estatus') && $request->estatus!=='Todas') {
            if ($request->estatus=='Inactivas'){
                $query = $query->where('inactivo', '=', 'X');
            }
            if ($request->estatus=='Activas'){
                $query = $query->where('inactivo', '=', NULL);
            }
        }
        return $query;
    }
}
