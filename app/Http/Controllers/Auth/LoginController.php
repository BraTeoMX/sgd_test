<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use DB;
use App\Tbl_Empleado_SIA;
use App\RolPermiso;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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
    }

    /**
     * Validate the user login.
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
    
                $mail = User::select 
                ('email')
                ->where('no_empleado',$request->usuario)
                ->get()->first();
            
                if($mail){
                    $request['email'] = $mail->email;
                }else{
                    $request['email'] ='prueba@gmail.com';
                }
                                                    
                    $this->validate(
                        $request,
                        [
                        // 'email' => 'required|string',
                            'password' => 'required|string',
                            'usuario' => 'required|string',
                        ],
                        [
                        //'email.required' => 'El correo electrónico es requerido',
                            'usuario.required' => 'El usuario es requerido',
                            'password.required' => 'La contraseña es requerida',
                        ]
                    );
            
            

    }

    public function usuarioregistro(){
        $usuario=Tbl_Empleado_SIA::WHERE('Status_Emp','A')->get();
        
        return view('usuarios.registronuevo', compact('usuario'));
    }


    public function validaregistro($id){

      $campos = explode('|',$id);      
      $no_empleado =  $campos[0];
      $nombre =  $campos[1];
      $correo =  $campos[2];
      $password = Hash::make($campos[3]);
      
        $user = new User();
            $user->create([
                'no_empleado' => $campos[0],
                'name' => $campos[1],
                'email' => $campos[2],
                'puesto' => 'PERSONAL ADMINISTRATIVO',
                'password' => $password,
            ]);
   
        $id_user = User::where('no_empleado',$campos[0])->get()->first();

        $data=array('role_id'=>'22',"model_type"=>'App\User',"model_id"=>$id_user->id);
        DB::table('model_has_roles')->insert($data);

        return redirect('home');
    
    }

   
}
