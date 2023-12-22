<?php

namespace App\Http\Controllers;

use App\Vacaciones;
use App\Tbl_Empleado_SIA;
use App\Puestos;
use App\Departamentos;
use App\permiso_vacaciones;
use App\Parametros;
use App\EventualidadPeriodo;
use App\User;

use App\Calendario;

use App\Imports\VacacionesmasivasImport;

use DB;

use App\Mail\SolicitudVacaciones;

use App\Mail\SolicitudDenegada;
use App\Mail\SolicitudAprobada;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Laracasts\Flash\Flash;
use JsValidator;
use File;
use App\Mail\envioCorreo;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;



class VacacionesController extends Controller
{

    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(Vacaciones $model)
    {

     $this->validationRules = [

            'no_empleado'=>'required',
            'nombre'=>'required',

       ];

       $this->attributeNames = [

           'no_empleado'=>'categoria',
           'nombre'=>'descripcion',

       ];

       $this->errorMessages = [

           'required' => 'El campo :attribute es requerido',

       ];

      $this->model = $model;

    }

    /*public function index(Request $request)*/
    public function index()
    {

      //  $jefe = Tbl_Empleado_SIA::select('No_Empleado')->where('email_Emp',auth()->user()->email)->where('status_Emp','A')->get()->first();
        $jefe = Tbl_Empleado_SIA::where('No_Empleado', str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT))->where('Status_Emp','A')
                ->get()->first();

        if(auth()->user()->hasRole('Jefe Administrativo')){
            $modulo='';
       }else{
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->select('Modulo')->get()->first();

            if(isset($obtener_modulo)){
                $modulo = $obtener_modulo->Modulo;
            }else{
                $modulo="";
            }
       }


       $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();
       $repetido=0;

        if($modulo=='' && !auth()->user()->hasRole('Gerente Produccion') && !auth()->user()->hasRole("Seguridad e Higiene")){

            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select
            ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','eventualidades','periodos')
           ->where('vacaciones2.jefe_directo',$jefe->No_Empleado)
           ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

        }else{
            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select
            ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','dias_solicitud','eventualidades','periodos')
            ->where('Modulo',$modulo)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

        }

        if(auth()->user()->hasRole('Jefe Administrativo') ){
            return view('vacaciones.index', compact('vacaciones','cont','parametros', 'repetido'));
        }else{
            if(auth()->user()->hasRole('Team Leader') || auth()->user()->hasRole('Gerente Produccion') || auth()->user()->hasRole("Coordinador/Analista") || auth()->user()->hasRole("Seguridad e Higiene") || auth()->user()->hasRole('Vicepresidente')){
                return view('vacaciones.form');
            }else{
                return redirect('/vacaciones.solicitarvacaciones');
            }
        }

/*
        if(auth()->user()->hasPermissionTo("vacaciones.solicitarvacaciones") && auth()->user()->hasPermissionTo("vacaciones.vacacionesLiberadas") && !auth()->user()->hasPermissionTo("usuario.index")){
               // return view('vacaciones.form');
                return redirect('/vacaciones.solicitarvacaciones');

        }else{
            if(auth()->user()->hasRole('Team Leader') || auth()->user()->hasRole('Vicepresidente'))
                return view('vacaciones.form');
            else{
                return view('vacaciones.index', compact('vacaciones','cont','parametros', 'repetido'));
            }
                 }*/
    }


    public function create(){

    }

    public function store(Request $reg_sol_vac){

        $vac = new Vacaciones;
        $vac->folio_vac = $this->getfolio($reg_sol_vac->id_planta);
        $vac->fecha_solicitud = date('Y-m-d');
        $vac->fk_no_empleado = $reg_sol_vac->no_empleado;
        $vac->dias_solicitud = $reg_sol_vac->dias_laborales;
        $vac->fech_ini_vac = $reg_sol_vac->inicio_vac;

        $existe = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','Vacaciones2.fk_no_empleado') //soslicitud pendiente sin respuesta
        ->where("fech_ini_vac","<=",$reg_sol_vac->inicio_vac)
        ->where("fech_fin_vac",">=",$reg_sol_vac->inicio_vac)
        ->where('modulo',$reg_sol_vac->modulo_emp)
        ->select('folio_vac')->get()->first();

        $repetido=0;

        if($existe){
            $repetido = 1;
        }else{
            $repetido = 0;
        }


            if($reg_sol_vac->fin_vac == null){
                $vac->fech_fin_vac = $reg_sol_vac->inicio_vac;
            }else{
                $vac->fech_fin_vac = $reg_sol_vac->fin_vac;
            }

            if($reg_sol_vac->eventualidad==1){
                $vac->eventualidades = '1';
                $data=array('no_empleado'=>$vac->fk_no_empleado,'eventualidad'=>'1','periodo'=>'0','anio'=>'2023','fecha'=>date("Y-m-d"));
                DB::table('eventualidad_periodo')->insert($data);
            }else{
                $vac->periodos = '1';
                $data=array('no_empleado'=>$vac->fk_no_empleado,'eventualidad'=>'0','periodo'=>'1','anio'=>'2023','fecha'=>date("Y-m-d"));
                DB::table('eventualidad_periodo')->insert($data);
            }

            if($reg_sol_vac->idjefe == null)
            {
                $obtener_jefe = Tbl_Empleado_SIA::where('No_Empleado', str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT))->where('Status_Emp','A')
                ->select('Puesto','Email_Emp')->get()->first();
                $vac->jefe_directo = $obtener_jefe->Puesto;
            }else{
                $obtener_jefe = Tbl_Empleado_SIA::where('No_Empleado', $reg_sol_vac->idjefe)->where('Status_Emp','A')
                ->select('Puesto','Email_Emp')->get()->first();
                $vac->jefe_directo = $obtener_jefe->Puesto;

            }
            if ($repetido==0){
                $vac->status = 'PENDIENTE';
            }else{
                $vac->status = 'CANCELADO';
            }

            $vac->save();

            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','Vacaciones2.fk_no_empleado')->where('Vacaciones2.fk_no_empleado',$reg_sol_vac->no_empleado)
            ->select('Modulo')->get()->first();

            $modulo = $obtener_modulo->Modulo;
            $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();


            if($modulo != ''){
                $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->select
                ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','dias_solicitud','eventualidades','periodos')
                ->where('Modulo',$modulo)
                ->orderby('fecha_solicitud','desc')
                ->get();
                $cont = $this->setUnique();
            }else{

                $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->select
                ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','dias_solicitud','eventualidades','periodos')
                ->where('Vacaciones2.folio_vac', $vac->folio_vac)
                ->orderby('fecha_solicitud','desc')
                ->get();
                $cont = $this->setUnique();


            }
        //    dd($modulo);
            $vac_email = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->select
            ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','eventualidades','periodos','status') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
            ->where('folio_vac',$vac->folio_vac)->where('status_Emp','A')
            ->get();
          //  dd(auth()->user()->hasRole('Coordinador/Analista'));
            if((auth()->user()->hasRole('Team Leader') ||  auth()->user()->hasRole('Gerente Produccion')) || auth()->user()->hasRole("Seguridad e Higiene") and $modulo != ''){
                return view('vacaciones.index', compact('vacaciones','cont','parametros','repetido'));
            }else{
               // dd($obtener_jefe->Email_Emp);


                if(auth()->user()->hasRole('Vicepresidente') || auth()->user()->hasRole('Coordinador/Analista') ){
                    return view('vacaciones.index', compact('vacaciones','cont','parametros','repetido'));
                }else{


                    $correo = User::where('no_empleado',ltrim($reg_sol_vac->idjefe,"0"))
                    ->select('email')->get()->first();
                    //dd($correo->email);

                   Mail::to($correo->email) // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
             //        Mail::to('gvergara@intimark.com.mx') // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
                      ->send(new SolicitudVacaciones($vac_email));
                    return redirect('/home');
                }

            }

    }

    public function getVacaciones(){
        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.ID_Empleado','=','Vacaciones2.fk_no_empleado')
        ->select
        ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','Modulo','Status_Emp','eventualidades','periodos')
        ->orwherenull('permiso_jefe')
        ->get();
        return $vacaciones;
    }

    public function getfolio($planta){
        $anio = date("Y");
        $ult_vac = Vacaciones::orderByDesc('IdVacaciones')->get()->first();
        if($ult_vac == NULL){
            $ult_vac = 1;
        }else{
            $ult_vac = $ult_vac->IdVacaciones+1;
        }
        $ult_vac = str_pad($ult_vac, 4, 0, STR_PAD_LEFT);
        if($planta == 'Intimark1'){
            $planta = 'A';
        }else{
            $planta = 'B';
        }
        $folio = $anio.$ult_vac.$planta;
        return $folio;
    }

    public function liberar($folio){

        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
        ->select
        ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','eventualidades','periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
        ->where('folio_vac','=',$folio)
        ->get();

        return view('vacaciones.liberar', compact('vacaciones'));
    }
    public function liberarPermiso($folio){


       /* $correo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
        ->select('Email_Emp')->WHERE('folio_vac','=',$folio)->first();*/
        $correo = Vacaciones::join('users','users.no_empleado','=','vacaciones2.fk_no_empleado')
        ->select('email')->WHERE('folio_vac','=',$folio)->first();


        if (auth()->user()->hasRole("Vicepresidente")){
            Vacaciones::WHERE('folio_vac',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'APLICADO','fecha_aprobacion'=>date("Y-m-d"),'excepcion'=>1]);
        }else{
            Vacaciones::WHERE('folio_vac',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'APLICADO','fecha_aprobacion'=>date("Y-m-d"),'excepcion'=>0]);
        }

      // Mail::to($correo->Email_Emp)->send(new envioCorreo($folio)); //envio de respuesta por correo de solicitud de vacaciones

      $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
      ->select
      ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','eventualidades','periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
      ->where('folio_vac','=',$folio)->where('status_Emp','A')
      ->get();

      if (auth()->user()->hasRole('Jefe Administrativo') ){

     //    Mail::to($correo->Email_Emp) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional
     Mail::to($correo->Email_Emp)
    // Mail::to('gvergara@intimark.com.mx')
     ->send(new SolicitudAprobada($vacaciones));  //Enviar correo de asolicitud aprobada
      }


        $vacaciones = $this->getVacaciones();

        $this->setMessage('Solicitud Aprobada');
        $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();


        if(auth()->user()->hasRole("Team Leader") || auth()->user()->hasRole('Jefe Administrativo')){
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->select('Modulo')->orderby('Modulo','desc')->get()->first();

            $modulo = $obtener_modulo->Modulo;

            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select
            ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','dias_solicitud','eventualidades','periodos')
            ->where('Modulo',$modulo)
            ->where('Email_Emp',auth()->user()->email)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

            //return view('vacaciones.index', compact('vacaciones','cont','parametros'));
            return redirect('/vacaciones');
        }else{
            return redirect('/vacaciones');
        }


    }

    public function liberarPermiso2($folio){


        /*$correo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
        ->select('Email_Emp')->WHERE('folio_vac','=',$folio)->first();*/
        $correo = Vacaciones::join('users','users.no_empleado','=','vacaciones2.fk_no_empleado')
        ->select('email')->WHERE('folio_vac','=',$folio)->first();

        Vacaciones::WHERE('folio_vac',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'APLICADO','fecha_aprobacion'=>date("Y-m-d")]);

      $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','vacaciones2.fk_no_empleado')
      ->select
      ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','eventualidades','periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
      ->where('folio_vac','=',$folio)->where('status_Emp','A')
      ->get();

      /*Mail::to($correo->Email_Emp) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional
          ->send(new SolicitudAprobada($vacaciones));*/  //Enviar correo de asolicitud aprobada
          Mail::to($correo->email) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional

          //dd($correo->email);
        //  Mail::to('gvergara@intimark.com.mx')
          ->send(new SolicitudAprobada($vacaciones));


        $vacaciones = $this->getVacaciones();

        $this->setMessage('Solicitud Aprobada');
        $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();

            return redirect('/home');

    }

    public function denegarPermiso($folio){
        $repetido=0;
        /*$correo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
        ->select('Email_Emp')->WHERE('folio_vac','=',$folio)->first();*/
        $correo = Vacaciones::join('users','users.no_empleado','=','vacaciones2.fk_no_empleado')
        ->select('email')->WHERE('folio_vac','=',$folio)->first();

        if (auth()->user()->hasRole("Vicepresidente")){
            Vacaciones::WHERE('folio_vac',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'CANCELADO','fecha_aprobacion'=>date("Y-m-d"),'excepcion'=>1]);
        }else{
            Vacaciones::WHERE('folio_vac',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'CANCELADO','fecha_aprobacion'=>date("Y-m-d"),'excepcion'=>0]);
        }

       // Mail::to($correo->Email_Emp)->send(new envioCorreo($folio));

        $vacaciones = $this->getVacaciones();

        $this->setMessage('Solicitud Denegada');

        if(auth()->user()->hasRole("Team Leader") || auth()->user()->hasRole('Jefe Administrativo')){
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->select('Modulo')->orderby('Modulo','desc')->get()->first();

            $modulo = $obtener_modulo->Modulo;
            $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();

            //$jefe = Tbl_Empleado_SIA::select('No_Empleado')->where('email_Emp',auth()->user()->email)->where('status_Emp','A')->get()->first();
            $jefe = Tbl_Empleado_SIA::where('No_Empleado', str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT))->where('Status_Emp','A')
                ->get()->first();

            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select
            ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','dias_solicitud','eventualidades','periodos')
            ->where('Modulo',$modulo)
            ->where('vacaciones2.jefe_directo',$jefe->No_Empleado)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

            return view('vacaciones.index', compact('vacaciones','cont','parametros','repetido'));
        }else{
            return redirect('/vacaciones');

        }

    }

    public function denegarPermiso2($folio){

        /*$correo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
        ->select('Email_Emp')->WHERE('folio_vac','=',$folio)->first();*/
        $correo = Vacaciones::join('users','users.no_empleado','=','Vacaciones2.fk_no_empleado')
        ->select('email')->WHERE('folio_vac','=',$folio)->first();

        Vacaciones::WHERE('folio_vac',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'CANCELADO','fecha_aprobacion'=>date("Y-m-d")]);

         $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
      ->select
      ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','eventualidades','periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
      ->where('folio_vac','=',$folio)->where('status_Emp','A')
      ->get();


       /*Mail::to($correo->Email_Emp) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional
          ->send(new SolicitudDenegada($vacaciones));*/  //Enviar correo de asolicitud denegada
          Mail::to($correo->email) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional
          //Mail::to('gvergara@intimark.com.mx')
          ->send(new SolicitudDenegada($vacaciones));



        $vacaciones = $this->getVacaciones();

        $this->setMessage('Solicitud Denegada');

        return redirect('/vacaciones');

    }

    public function enviarCorreo(){

    }

    public function vac_liberadas(){
        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select
            ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','eventualidades','periodos')

           ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

        return view('vacaciones.vacaciones_liberadas', compact('vacaciones','cont'));


    }

    public function cancelacion(){
        //$jefe = Tbl_Empleado_SIA::select('No_Empleado')->where('email_Emp',auth()->user()->email)->where('status_Emp','A')->get()->first();
        $jefe = Tbl_Empleado_SIA::where('No_Empleado', str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT))->where('Status_Emp','A')
                ->get()->first();

        if(auth()->user()->hasRole('Jefe Administrativo')){
            $modulo='';
       }else{
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->select('Modulo')->get()->first();

            if(isset($obtener_modulo)){
                $modulo = $obtener_modulo->Modulo;
            }else{
                $modulo="";
            }
       }

       $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();
       $repetido=0;



            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select
            ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','eventualidades','periodos')
           ->orderby('fech_ini_vac','desc')
            ->get();
            $cont = $this->setUnique();

            return view('vacaciones.cancelacion', compact('vacaciones','cont','parametros', 'repetido'));


    }

    public function show(Request $noEmpleado){

    }

    public function buscarEmp(Request $noEmpleado){

    }

    public function edit(Request $categoria){

    }

    public function update2($folio){
        $repetido=0;
        Vacaciones::where('folio_vac',$folio)
        ->update([
        'status'=>'CANCELADO','fecha_aprobacion'=>date("Y-m-d"),
        'usuario_actualizacion_id'=>auth()->user()->id
        ]);

        //$jefe = Tbl_Empleado_SIA::select('No_Empleado')->where('email_Emp',auth()->user()->email)->where('status_Emp','A')->get()->first();
        $jefe = Tbl_Empleado_SIA::where('No_Empleado', str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT))->where('Status_Emp','A')
                ->get()->first();

        if(auth()->user()->hasRole('Jefe Administrativo')){
            $modulo='';
       }else{
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->select('Modulo')->get()->first();

            if(isset($obtener_modulo)){
                $modulo = $obtener_modulo->Modulo;
            }else{
                $modulo="";
            }
       }

       $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();
       $repetido=0;


            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select
            ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','eventualidades','periodos')
           ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

            return view('vacaciones.cancelacion', compact('vacaciones','cont','parametros', 'repetido'));
          // return redirect('/vacaciones');
    }

    public function update($folio){
        $repetido=0;
        Vacaciones::where('folio_vac',$folio)
        ->update([
        'status'=>'CANCELADO','fecha_aprobacion'=>date("Y-m-d"),
        'usuario_actualizacion_id'=>auth()->user()->id
        ]);

        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
        ->select
        ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','eventualidades','periodos','status') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
        ->where('folio_vac','=',$folio)->where('status_Emp','A')
        ->get();


        //Mail::to($correo->Email_Emp) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional
       //Mail::to('gvergara@intimark.com.mx')
       //->send(new SolicitudDenegada($vacaciones));  //Enviar correo de asolicitud denegada


       if(auth()->user()->hasRole("Team Leader") || auth()->user()->hasRole('Jefe Administrativo')){
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->select('Modulo')->orderby('Modulo','desc')->get()->first();

            $modulo = $obtener_modulo->Modulo;
            $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();


            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select
            ('folio_vac','fecha_solicitud','fech_ini_vac','fech_fin_vac','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','dias_solicitud','eventualidades','periodos')
            ->where('Modulo',$modulo)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

            return view('vacaciones.index', compact('vacaciones','cont','parametros','repetido'));
        }  else{

            return redirect('/vacaciones');
        }

    }


    public function destroy(Request $folio){

    }

    public function solicitarvacaciones(){

        $email = auth()->user()->email;

        date_default_timezone_set('America/Mexico_City');

        $fechaActual = date('d-m-Y');
        $fechaSegundos = strtotime($fechaActual);
        $semana = date('W', $fechaSegundos);

        if($semana > 49 ){
            echo"<script type='text/javascript'>
            alert('Por cierre Anual, ya no se permiten generar Solicitudes de Vacaciones');
            window.location.href='/home';
            </script>";

        }

        $no_empleado =str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT); ;

        $datosEmpleado = Tbl_Empleado_SIA::WHERE('Status_Emp','A')->WHERE('Tbl_empleados_SIA.No_Empleado',$no_empleado)
        ->groupby('Tbl_Empleados_SIA.No_Empleado')->get();

        $getdep = Tbl_Empleado_SIA::WHERE('Status_Emp','A')->WHERE('No_Empleado',$no_empleado)
        ->get()->first();
        $tag = $getdep->No_Empleado;

        if(isset($getdep)){

             $dep = $getdep->Departamento;
            $cveci2 =  $getdep->cveci2;
            $puesto =  $getdep->Puesto;
            $anio = date("Y");

            $anio = date('Y');
            $mes = date('m');
            $dia = date('d');


            $anio2 = strtotime ('+1 year' , strtotime($anio)); //Se añade un año mas
            $anio2 = date ('Y',$anio2);

            $anio3 = strtotime ('-1 year' , strtotime($anio)); //Se añade un año mas
            $anio3 = date ('Y',$anio3);

            $datos = explode("-", $getdep->Fecha_In);
            $anio_antiguedad = $datos[0];
            $mes_antiguedad = $datos[1];
            $dia_antiguedad = $datos[2];
            $antiguedad= $anio.'-'.$mes_antiguedad.'-'.$dia_antiguedad;

            if($antiguedad <= $anio.'-'.$mes.'-'.$dia and $antiguedad >= $anio.'-01-01'){
                $antiguedad2 = $anio2.'-'.$mes_antiguedad.'-'.$dia_antiguedad;

                $eventualidad = EventualidadPeriodo::WHERE('no_empleado',$tag)->where('fecha','<=',$antiguedad2)->where('fecha','>=',$antiguedad)->sum('eventualidad');
                $periodo = EventualidadPeriodo::WHERE('no_empleado',$tag)->where('fecha','<=',$antiguedad2)->where('fecha','>=',$antiguedad)->sum('periodo');


            }else{
                $antiguedad3 = $anio3.'-'.$mes_antiguedad.'-'.$dia_antiguedad;

//            dd($antiguedad3);
                $eventualidad = EventualidadPeriodo::WHERE('no_empleado',$tag)->where('fecha','>=',$antiguedad3)->where('fecha','<=',$antiguedad)->sum('eventualidad');
                $periodo = EventualidadPeriodo::WHERE('no_empleado',$tag)->where('fecha','>=',$antiguedad3)->where('fecha','<=',$antiguedad)->sum('periodo');

            }
        }else{
            $dep = '';
            $puesto = '';
            $cveci2='';
            $antiguedad='';
        }

        $puestos = Puestos::WHERE('Status','=','A')->get();

        $departamentos = Departamentos::WHERE('Status','=','A')->get();

        $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();

       // $eventualidad = EventualidadPeriodo::WHERE('no_empleado',$no_empleado)->sum('eventualidad');
       // $periodo = EventualidadPeriodo::WHERE('no_empleado',$no_empleado)->sum('periodo');

        $vacaciones= Vacaciones::where('fk_no_empleado',$tag)->where('status','<>','CANCELADO')->get();

        $datosJefe = Tbl_Empleado_SIA::join('permiso_vac','Tbl_Empleados_SIA.Puesto','=','permiso_vac.id_jefe')->where('Status_Emp','=','A')
        ->where('id_puesto_solicitante',$cveci2)->where('id_jefe','<>',$puesto)->orderby('Nom_Emp')->get();

        return view('vacaciones.solicitud_vac', compact('datosEmpleado','datosJefe','puestos','departamentos','vacaciones','parametros','eventualidad','periodo'));
    }

    public function registrar_sol_vac(Request $reg_sol_vac){

        $vac = new Vacaciones;
        $vac->Area_emp = $reg_sol_vac->Departamento;
        $vac->jefe_directo = '1';
        $vac->create($vac);
        Vacaciones::create($reg_sol_vac->all());
        return view('vacaciones.form');
    }



    private function normalizaDatos(Request $request, $id=0){

        if($id == 0){
            $request['usuario_creacion_id'] = auth()->user()->id;
        }else{
            $request['usuario_actualizacion_id'] = auth()->user()->id;
        }

        return $request;

    }



    protected function setValidator(Request $request, $id=0){

        return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);

    }



    private function setQuery($request){

        $query = $this->model;
        if ($request->filled('No_emp')) {
            $query = $query->where('No_emp', '=',$request->input('No_emp'))
                ->where('Status_Emp', '=','A') ;
       }
        return $query;

    }



     //retorno de mensajes en el proyecto

    private function setMessage($mensaje){

         return  flash($mensaje)->success()->important();

     }



    private function setUnique(){

        //return Vacaciones::where('folio_vac','=',$request->input('folio_vac'))->count();
        return Vacaciones::all()->count();

    }

    public function viewAjax(Request $request){
        $fech = $request->fecha;
        $pago = $request->pago;

        if ($pago == 'SEMANAL'){
            $frec_pago=1;
        }else{
            if ($pago == 'QUINCENAL'){
                $frec_pago=2;
            }
        }
        $nomina = Calendario::where('fecha_calendario',$fech)->get()->first();

        if ($nomina->tipo_nomina==3){
            $datos=1;
        }else{
            $datos = Calendario::where('fecha_calendario',$fech)->where('tipo_nomina',$frec_pago)->get()->count();
        }

        return $datos;
        //return 'holaa';

    }

        public function viewAjax2(Request $request){
            $empleado = $request->empleado;
            $datos = Vacaciones::where('fk_no_empleado',$empleado)->where('status','PENDIENTE')->get()->count();
            return $datos;


    }

    public function viewAjax3(Request $request){
        $fech = $request->fecha;
        $modulo = $request->modulo;
        $departamento = $request->departamento;

        if($modulo==''){
            $no_empleados = Tbl_Empleado_SIA::where('Departamento',$departamento)->where('status_Emp','A')->get()->count();

            $parametros = Parametros::select('valor')->where('Status','=','A')->where('clave','aus_vac')->where('modulo','1')->get()->first();

            $porc_ausentismo = $no_empleados * $parametros->valor;

            $datos = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->where('fech_ini_vac','<=',$fech)->where('fech_fin_vac','>=',$fech)->where('Tbl_Empleados_SIA.Departamento',$departamento)->get()->count();

            if(ceil($porc_ausentismo) > $datos ){
                $datos = 0;
            }else{
                $datos = 1;
            }
        }else{
            $datos = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
            ->where('fech_ini_vac','<=',$fech)->where('fech_fin_vac','>=',$fech)->where('Tbl_Empleados_SIA.Modulo',$modulo)->get()->count();
        }


        return $datos;

    }

    public function masivas(){
        return view('vacaciones.masivas');
    }

    public function importar(Request $request){
        #echo 'hola desde el importarsssssss';
        $import = new VacacionesmasivasImport;
        $file = $request->file('file');
        Excel::import($import, $file);
        return view('vacaciones.masivas')->with('exito', 'ok');

    }

    public function saldo(Request $request){
        return view('vacaciones.saldo');

    }

    public function saldoempleado(Request $request){

        $hoy=date('Y-m-d');
        $tag = substr($request->no_empleado,-9);
        $tag = str_pad($tag, 7, "0", STR_PAD_LEFT);

        $saldo =  Tbl_Empleado_SIA::WHERE('Status_Emp','=','A')->WHERE('No_Empleado','=',$tag)
        ->orWHERE('Status_Emp','=','A')->WHERE('No_TAG','=',$tag)
        ->get();

        return view('vacaciones.saldo', compact('saldo') );

    }




}
