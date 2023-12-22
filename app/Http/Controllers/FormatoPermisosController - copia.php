<?php

namespace App\Http\Controllers;

use App\FormatoPermisos;
use App\Tbl_Empleado_SIA;
use App\Puestos;
use App\Departamentos;
use App\Vacaciones;
use App\Parametros;
use App\EventualidadPeriodo;
use App\User;

use App\Calendario;

use DB;

use App\Mail\SolicitudPermisos;

use App\Mail\SolicitudDenegada;
use App\Mail\PermisoAprobada;
use App\Mail\PermisoAprobadaSM;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Laracasts\Flash\Flash;
use JsValidator;
use File;
use App\Mail\envioCorreo;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;



class FormatoPermisosController extends Controller
{

    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $model;

    public function __construct(formatopermisos $model)
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

    public function index()
    {
       
        if(auth()->user()->hasRole('Jefe Administrativo')){
            $modulo='';  
       }else{
            $obtener_modulo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->select('Modulo')->get()->first();

            if(isset($obtener_modulo)){
                $modulo = $obtener_modulo->Modulo;
            }else{
                $modulo="";
            }
       }

       $parametros = Parametros::WHERE('Status','=','A')->where('modulo','2')->get();
       $repetido=0;
             
        if($modulo=='' && !auth()->user()->hasRole('Gerente Produccion') && !auth()->user()->hasRole("Seguridad e Higiene")){

            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo')
           ->where('permisos.jefe_directo',auth()->user()->no_empleado)
           ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

        }else{
            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','dias')
            ->where('Modulo',$modulo)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();
            
        }

      /*  if(auth()->user()->hasRole('Jefe Administrativo') || auth()->user()->hasRole('Vicepresidente')){
            return view('FormatoPermisos.index', compact('permisos','cont','parametros', 'repetido'));
        }else{
            if(auth()->user()->hasRole('Team Leader') || auth()->user()->hasRole('Gerente Produccion') || auth()->user()->hasRole("Coordinador/Analista") || auth()->user()->hasRole("Seguridad e Higiene")){
                */
                return view('formatopermisos.form');
           /* }else{
                return redirect('/formatopermisos.solicitar');
            }
        }    */

    }


    public function create(){

    }

    public function store(Request $reg_sol_per){
        
        //dd($reg_sol_per->all());
        $per = new FormatoPermisos;
        $per->folio_per = $this->getfolio($reg_sol_per->id_planta);
        $per->fecha_solicitud = date('Y-m-d');
        $per->fk_no_empleado = $reg_sol_per->no_empleado;
        $per->tipo_per = $reg_sol_per->motivo;
        $per->modo_per = $reg_sol_per->modo_permiso;
        $per->excepcion = $reg_sol_per->excepcion;

        if(isset($per->excepcion)){
            $per->excepcion = 1;
        }else{
            $per->excepcion = 0;
        }

       // dd($reg_sol_per->all());
                
        if($reg_sol_per->inicio_diahor <> ''){
            $per->fech_ini_per = $reg_sol_per->inicio_diahor;
            $per->fech_fin_per = $reg_sol_per->inicio_diahor;
            $per->fech_ini_hor = $reg_sol_per->inicio_hor;
            $per->fech_fin_hor = $reg_sol_per->fin_hor;
            $per->dias = 1;
            $per->horas = $reg_sol_per->horas;
            

            $existe = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','permisos.fk_no_empleado') //solicitud pendiente sin respuesta
            ->where("fech_ini_per","=",$reg_sol_per->inicio_diahor)
            ->where('modulo',$reg_sol_per->modulo_emp)
            ->where('status','PENDIENTE')
            ->select('folio_per')->get()->first();
        }else{
            $per->fech_ini_per = $reg_sol_per->inicio_per;
            if($reg_sol_per->inicio_per == ''){
                $per->fech_fin_per = $reg_sol_per->inicio_per;
            }else{
                $per->fech_fin_per = $reg_sol_per->fin_per;
            }
            $per->dias = $reg_sol_per->dias;   

            $existe = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','permisos.fk_no_empleado') //solicitud pendiente sin respuesta
            ->where("fech_ini_per","<=",$reg_sol_per->inicio_per)
            ->where("fech_fin_per",">=",$reg_sol_per->fin_per)
            ->where('modulo',$reg_sol_per->modulo_emp)
            ->where('status','PENDIENTE')
            ->select('folio_per')->get()->first();
        }        

        if($existe){
            $repetido = 1;
        }else{
            $repetido = 0;
        }         
        
       
        if($reg_sol_per->autorizar != null)
        {
            $obtener_jefe = Tbl_Empleado_SIA::where('No_Empleado', $reg_sol_per->autorizar)->where('Status_Emp','A')
             ->select('Puesto','No_Empleado','Email_Emp')->get()->first();
            $per->jefe_directo = $obtener_jefe->Puesto;
               
        }else{
            $per->jefe_directo = '';
        }
      //  if ($repetido==0){
            $per->status = 'PENDIENTE';
        /*}else{
            $per->status = 'CANCELADO';
        }*/
        $per->obs = $reg_sol_per->obs;

        $per->save();

           $obtener_modulo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','permisos.fk_no_empleado')->where('permisos.fk_no_empleado',$reg_sol_per->no_empleado)
            ->select('Modulo')->get()->first();

           $modulo = $obtener_modulo->Modulo;
            $parametros = Parametros::WHERE('Status','=','A')->where('modulo','2')->get();
          
            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','dias','horas')
            ->where('Modulo',$modulo)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

            $per_email = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_permisos','cat_permisos.id_permiso','=','permisos.tipo_per')
            ->select 
            ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','status','permiso','obs') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
            ->where('folio_per',$per->folio_per)->where('status_Emp','A')
            ->get();

            $per_nivel = Tbl_Empleado_SIA:: join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->where('No_Empleado', $reg_sol_per->autorizar)->where('Status_Emp','A')
            ->select('Nivel')->get()->first();
             
            if($per_nivel->Nivel == 1){

                $correo = User::where('no_empleado',ltrim($reg_sol_per->autorizar,"0"))
                ->select('email')->get()->first();
             
                  Mail::to($correo->email) 
                //    Mail::to('gvergara@intimark.com.mx') // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
                    ->send(new SolicitudPermisos($per_email));
              
                return redirect('/home');
            }else{
                if(auth()->user()->hasRole("Coordinador/Analista")){
                    return view('formatopermisos.form');
                }else{
                    return view('formatopermisos.liberar', compact('permisos','cont','parametros','repetido'));
                }
                
            }
        //dd($vp->Nivel);
       //     if((auth()->user()->hasRole('Team Leader') ||  auth()->user()->hasRole('Gerente Produccion')) || auth()->user()->hasRole("Seguridad e Higiene") and $modulo != ''){
         //       return view('permisos.index', compact('permisos','cont','parametros','repetido'));
           // }else{
             // Mail::to($obtener_jefe->Email_Emp) // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
           /*   Mail::to('gvergara@intimark.com.mx') // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
                ->send(new SolicitudPermisos($per_email));  
    */
          //      if(auth()->user()->hasRole('Vicepresidente') ){
             //       return view('formatopermisos.liberar', compact('permisos','cont','parametros','repetido'));
            //    }else{
              //      return redirect('/home');
              //  }     
                
            //}
        
    }

    public function getPermisos(){
        $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.ID_Empleado','=','permisos.fk_no_empleado')
        ->select 
        ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','Modulo','Status_Emp')
        ->orwherenull('permiso_jefe')
        ->get();
        return $permisos;
    }

    public function getfolio($planta){
        $anio = date("Y");
        $ult_per = FormatoPermisos::orderByDesc('IdPermiso')->get()->first();
        if($ult_per == NULL){
            $ult_per = 1;            
        }else{
            $ult_per = $ult_per->IdPermiso+1;
        }
        $ult_per = str_pad($ult_per, 4, 0, STR_PAD_LEFT);
        if($planta == 'Intimark1'){
            $planta = 'A';
        }else{
            $planta = 'B';
        }        
        $folio = $anio.$ult_per.$planta;
        return $folio;
    }
    
    public function liberar($folio){
      
        $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
        ->select 
        ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','eventualidades','periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
        ->where('folio_per','=',$folio)
        ->get();
        
        return view('FormatoPermisos.liberar', compact('FormatoPermisos'));
    }
    public function liberarPermisos($folio){

        
       // $correo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
       // ->select('Email_Emp')->WHERE('folio_per','=',$folio)->first();
        if (auth()->user()->hasRole("Vicepresidente")){
            FormatoPermisos::WHERE('folio_per',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'APLICADO','fecha_aprobacion'=>date("Y-m-d"),'excepcion'=>1]);
        }else{
            FormatoPermisos::WHERE('folio_per',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'APLICADO','fecha_aprobacion'=>date("Y-m-d"),'excepcion'=>0]);
        }
        
      // Mail::to($correo->Email_Emp)->send(new envioCorreo($folio)); //envio de respuesta por correo de solicitud de FormatoPermisos

      $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
      ->select 
      ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','obs') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
      ->where('folio_per','=',$folio)->where('status_Emp','A')
      ->get();
      
     // if (auth()->user()->hasRole('Jefe Administrativo') || auth()->user()->hasRole('Vicepresidente')){
    //  Mail::to($correo->Email_Emp) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional
      //    ->send(new SolicitudAprobada($permisos));  //Enviar correo de asolicitud aprobada
      //    Mail::to('gvergara@intimark.com.mx') // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
       //   ->send(new SolicitudAprobada($permisos));  
     // }
 

      //  $permisos = $this->getFormatoPermisos();
       
        $this->setMessage('Solicitud Aprobada');
        $parametros = Parametros::WHERE('Status','=','A')->where('modulo','2')->get();

        
     /*   if(auth()->user()->hasRole('Servicio Medico')){
            Mail::to('gvergara@intimark.com.mx') // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
            ->send(new PermisoAprobadaSM($permisos));  
        }*/
        

        if(auth()->user()->hasRole("Team Leader") || auth()->user()->hasRole('Jefe Administrativo') ){
            $obtener_modulo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->select('Modulo')->orderby('Modulo','desc')->get()->first();

           $modulo = $obtener_modulo->Modulo;

            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','dias','obs')
            ->where('Modulo',$modulo)
            ->where('Email_Emp',auth()->user()->email)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

           
                       
            //return view('FormatoPermisos.index', compact('FormatoPermisos','cont','parametros'));
            return redirect('/formatopermisos');
        }else{
            return redirect('/formatopermisos');
        }
        
        
    }

    public function liberarPermiso2($folio){

      
     /*   $correo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
        ->select('Email_Emp')->WHERE('folio_per','=',$folio)->first();
*/
    FormatoPermisos::WHERE('folio_per',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'APLICADO','fecha_aprobacion'=>date("Y-m-d")]);
    
      $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
      ->select 
      ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','obs') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
      ->where('folio_per','=',$folio)->where('status_Emp','A')
      ->get();
      
    //  Mail::to($correo->Email_Emp) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional
      //    ->send(new SolicitudAprobada($permisos));  //Enviar correo de asolicitud aprobada
        //  Mail::to('gvergara@intimark.com.mx') // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
          //->send(new PermisoAprobada($permisos));  

       /* $permisos = $this->getPermisos();
       
        $this->setMessage('Solicitud Aprobada');
        $parametros = Parametros::WHERE('Status','=','A')->where('modulo','2')->get();
        */
            return redirect('/formatopermisos');
       
        
    }


    public function denegarPermiso($folio){
        $repetido=0;
        $correo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
        ->select('Email_Emp')->WHERE('folio_per','=',$folio)->first();
        if (auth()->user()->hasRole("Vicepresidente")){
            FormatoPermisos::WHERE('folio_per',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'CANCELADO','fecha_aprobacion'=>date("Y-m-d"),'excepcion'=>1]);
        }else{
            FormatoPermisos::WHERE('folio_per',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'CANCELADO','fecha_aprobacion'=>date("Y-m-d"),'excepcion'=>0]);
        }
        
       // Mail::to($correo->Email_Emp)->send(new envioCorreo($folio));

        $permisos = $this->getFormatoPermisos();
        
        $this->setMessage('Solicitud Denegada');

        if(auth()->user()->hasRole("Team Leader") || auth()->user()->hasRole('Jefe Administrativo')){
            $obtener_modulo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->select('Modulo')->orderby('Modulo','desc')->get()->first();

            $modulo = $obtener_modulo->Modulo;
            $parametros = Parametros::WHERE('Status','=','A')->where('modulo','2')->get();
        
            $jefe = Tbl_Empleado_SIA::select('No_Empleado')->where('email_Emp',auth()->user()->email)->where('status_Emp','A')->get()->first();
            
            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','dias','eventualidades','periodos')
            ->where('Modulo',$modulo)
            ->where('permisos.jefe_directo',$jefe->No_Empleado)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();
                       
            return view('FormatoPermisos.index', compact('FormatoPermisos','cont','parametros','repetido'));
        }else{
            return redirect('/FormatoPermisos');
       
        }
        
    }

    public function denegarPermiso2($folio){

       /* $correo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
        ->select('Email_Emp')->WHERE('folio_per','=',$folio)->first();*/

        $correo = FormatoPermisos::join('users','users.no_empleado','=','permisos.fk_no_empleado')
        ->select('email')->WHERE('folio_per','=',$folio)->first();

        FormatoPermisos::WHERE('folio_per',$folio)->UPDATE(['permiso_jefe'=>1,'status'=>'CANCELADO','fecha_aprobacion'=>date("Y-m-d")]);

         $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
      ->select 
      ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','eventualidades','periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
      ->where('folio_per','=',$folio)->where('status_Emp','A')
      ->get();
      
      
       Mail::to($correo->email) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional
          ->send(new SolicitudDenegada($permisos));  //Enviar correo de asolicitud denegada
        


        $permisos = $this->getFormatoPermisos();
        
        $this->setMessage('Solicitud Denegada');

        return redirect('/FormatoPermisos');
        
    }

    public function enviarCorreo(){

    }

    public function vac_liberadas(){
        $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','eventualidades','periodos')
        
           ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();
       
        return view('FormatoPermisos.FormatoPermisos_liberadas', compact('FormatoPermisos','cont'));

        
    }

    public function cancelacion(){
        $jefe = Tbl_Empleado_SIA::select('No_Empleado')->where('email_Emp',auth()->user()->email)->where('status_Emp','A')->get()->first();

        if(auth()->user()->hasRole('Jefe Administrativo')){
            $modulo='';  
       }else{
            $obtener_modulo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->select('Modulo')->get()->first();

            if(isset($obtener_modulo)){
                $modulo = $obtener_modulo->Modulo;
            }else{
                $modulo="";
            }
       }
        
       $parametros = Parametros::WHERE('Status','=','A')->where('modulo','2')->get();
       $repetido=0;
             
       

            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','eventualidades','periodos')
           ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

            return view('FormatoPermisos.cancelacion', compact('FormatoPermisos','cont','parametros', 'repetido'));
       
        
    }

    public function show(Request $noEmpleado){
       
    }

    public function buscarEmp(Request $noEmpleado){
      
    }

    public function edit(Request $categoria){

    }

    public function update2($folio){
        
       // dd($folio);
        FormatoPermisos::where('folio_per',$folio)
        ->update([
        'status'=>'CANCELADO','fecha_aprobacion'=>date("Y-m-d"),
        'usuario_actualizacion_id'=>auth()->user()->id
        ]);
      
        return redirect('/estatuspermisos');
    }

    public function update($folio){
        
        $repetido=0;
        FormatoPermisos::where('folio_per',$folio)
        ->update([
        'status'=>'CANCELADO','fecha_aprobacion'=>date("Y-m-d"),
        'usuario_actualizacion_id'=>auth()->user()->id
        ]);
      
        $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
        ->select 
        ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','Puesto','Departamento','status') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
        ->where('folio_per','=',$folio)->where('status_Emp','A')
        ->get();
        
        
       // Mail::to($correo->Email_Emp) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional
        //    ->send(new SolicitudDenegada($permisos));  //Enviar correo de asolicitud denegada
 
 
       if(auth()->user()->hasRole("Team Leader") || auth()->user()->hasRole('Jefe Administrativo')){
            $obtener_modulo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->select('Modulo')->orderby('Modulo','desc')->get()->first();

            $modulo = $obtener_modulo->Modulo;
            $parametros = Parametros::WHERE('Status','=','A')->where('modulo','2')->get();
        

            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','dias')
            ->where('Modulo',$modulo)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();
                       
            return view('formatopermisos.form', compact('permisos','cont','parametros','repetido'));
        }  else{

            return redirect('/formatopermisos');
        }  
        
    }


    public function destroy(Request $folio){
        
    }

    public function solicitar(){
      
        $email = auth()->user()->email;

        date_default_timezone_set('America/Mexico_City');

        $fechaActual = date('d-m-Y');	
        $fechaSegundos = strtotime($fechaActual);	      
        $semana = date('W', $fechaSegundos);	
        
    /*    if($semana > 49 ){
            echo"<script type='text/javascript'>
            alert('Por cierre Anual, ya no se permiten generar Solicitudes de Permisos');
            window.location.href='/home';
            </script>";
           
        }*/
       
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
            $anio2 = strtotime ('+1 year' , strtotime($anio)); //Se añade un año mas
            $anio2 = date ('Y',$anio2);   

            $anio3 = strtotime ('-1 year' , strtotime($anio)); //Se añade un año mas
            $anio3 = date ('Y',$anio3);  

            $datos = explode("-", $getdep->Fecha_In);
            $anio_antiguedad = $datos[0];
            $mes_antiguedad = $datos[1];
            $dia_antiguedad = $datos[2];
            $antiguedad= $anio.'-'.$mes_antiguedad.'-'.$dia_antiguedad;
            if($antiguedad <= $anio.'-03-21' and $antiguedad >= $anio.'-01-01'){
                $antiguedad2 = $anio2.'-'.$mes_antiguedad.'-'.$dia_antiguedad;
                
                $eventualidad = EventualidadPeriodo::WHERE('no_empleado',$no_empleado)->where('fecha','<=',$antiguedad2)->where('fecha','>=',$antiguedad)->sum('eventualidad');
                $periodo = EventualidadPeriodo::WHERE('no_empleado',$no_empleado)->where('fecha','<=',$antiguedad2)->where('fecha','>=',$antiguedad)->sum('periodo');
        
                
            }else{
                $antiguedad3 = $anio3.'-'.$mes_antiguedad.'-'.$dia_antiguedad;

                $eventualidad = EventualidadPeriodo::WHERE('no_empleado',$no_empleado)->where('fecha','<=',$antiguedad3)->where('fecha','>=',$antiguedad)->sum('eventualidad');
                $periodo = EventualidadPeriodo::WHERE('no_empleado',$no_empleado)->where('fecha','<=',$antiguedad3)->where('fecha','>=',$antiguedad)->sum('periodo');
        
            }
        }else{
            $dep = '';
            $puesto = '';
            $cveci2='';
            $antiguedad='';
        }

        $puestos = Puestos::WHERE('Status','=','A')->get();
        
        $departamentos = Departamentos::WHERE('Status','=','A')->get();

        $parametros = Parametros::WHERE('Status','=','A')->where('modulo','2')->get();

      //  $eventualidad = EventualidadPeriodo::WHERE('no_empleado',$no_empleado)->where('fecha','<',$antiguedad)->sum('eventualidad');
       // $periodo = EventualidadPeriodo::WHERE('no_empleado',$no_empleado)->where('fecha','<',$antiguedad)->sum('periodo');
        
        $permisos= FormatoPermisos::join('cat_permisos','permisos.tipo_per','=','cat_permisos.id_permiso')
        ->where('fk_no_empleado',$tag)
        ->where('status','<>','CANCELADO')
        ->where('cat_permisos.acumula',1)
        ->whereMonth('fech_ini_per',date("m"))
        ->whereYear('fech_ini_per',date("Y"))
        ->get()->first();
        if($permisos<> null){
            $no_permiso = 1;
        }else{
            $no_permiso = 0;
        }

        $nivel = Tbl_Empleado_SIA::join('cat_puestos','Tbl_Empleados_SIA.Puesto','cat_puestos.id_puesto')->select('Nivel')
        ->where('Status_Emp','=','A')->where('No_Empleado',$no_empleado)->get()->first();

        $datosJefe = Tbl_Empleado_SIA::join('permiso_vac','Tbl_Empleados_SIA.Puesto','=','permiso_vac.id_jefe')->join('cat_puestos','Tbl_Empleados_SIA.Puesto','cat_puestos.id_puesto')
        ->where('Status_Emp','=','A')
        ->where('id_puesto_solicitante',$cveci2)
        ->where('id_jefe','<>',$puesto)
       // ->where('Nivel','<',$nivel->Nivel)
        ->orderby('Nom_Emp')->get();
        
        return view('formatopermisos.index', compact('datosEmpleado','datosJefe','puestos','departamentos','parametros','no_permiso'));
    }

    public function registrar_sol_per(Request $reg_sol_per){
        
        $vac = new FormatoPermisos;
        $vac->Area_emp = $reg_sol_per->Departamento;
        $vac->jefe_directo = '1';
        $vac->create($vac);
        FormatoPermisos::create($reg_sol_per->all());
        return view('FormatoPermisos.form');
    }

    public function revisarpermiso($id){
        /*date_default_timezone_set('America/Mexico_City');
        $fecha = Carbon::now();
        $dia = $fecha->format('Y-m-d');
        $hora = $fecha->format('H:i:s');*/

        $fecha=(localtime(time(),true));
        if($fecha["tm_isdst"]==1){
            $hora=$fecha["tm_hour"]-1;
            $dia = $fecha["tm_year"].'-'.$fecha["tm_mon"].'-'.$fecha["tm_mday"];
            $hora = $hora.':'.$fecha["tm_min"].':'.$fecha["tm_sec"];
        }else{
            $dia = $fecha["tm_year"].'-'.$fecha["tm_mon"].'-'.$fecha["mday"];
            $hora = $fecha["tm_hour"].':'.$fecha["tm_min"].':'.$fecha["tm_sec"];
        }
//dd($hora);
        $folio=$id;
        $permiso = FormatoPermisos::where('status','APLICADO')
        ->where('folio_per', $id)
        ->orderby('fech_ini_hor','asc')
        ->get()->first();

        
        if($permiso != NULL){
               $salida = FormatoPermisos::where('folio_per', $id)->update(['fech_ini_hor' => $hora,'firmado' => 1]);
               if($salida == true){
            }
        }
     //  return view('formatopermisos.seguridad');
        if($permiso->firmado == 1){
            return view('formatopermisos.seguridad');
        }else{
            return view('formatopermisos.firma', compact('folio'));
        }
        
    }

    public function revisarEntrada($folio){
        /*date_default_timezone_set('America/Mexico_City');
        $fecha = Carbon::now();
        $dia = $fecha->format('Y-m-d');
        $hora = $fecha->format('H:i:s');*/

        $fecha=(localtime(time(),true));
        if($fecha["tm_isdst"]==1){
            $hora=$fecha["tm_hour"]-1;
            $dia = $fecha["tm_year"].'-'.$fecha["tm_mon"].'-'.$fecha["tm_mday"];
            $hora = $hora.':'.$fecha["tm_min"].':'.$fecha["tm_sec"];
        }else{
            $dia = $fecha["tm_year"].'-'.$fecha["tm_mon"].'-'.$fecha["mday"];
            $hora = $fecha["tm_hour"].':'.$fecha["tm_min"].':'.$fecha["tm_sec"];
        }
        $permiso = FormatoPermisos::where('status','APLICADO')
        ->where('folio_per', $folio)
        ->orderby('fech_ini_hor','asc')
        ->get()->first();

        if($permiso != NULL){
            $salida = FormatoPermisos::where('folio_per', $folio)->update(['fech_fin_hor' => $hora,'firmado' => 1]);
            if($salida == true){
            }else{
            }
        }else{
        }
        if($permiso->firmado == 1){
            return view('formatopermisos.seguridad');
        }else{
            return view('formatopermisos.firma', compact('folio'));
        }

    }

    public function permisofirma($id){
        
        $folio=$id;
        $permiso = FormatoPermisos::where('status','APLICADO')
        ->where('folio_per', $id)
        ->orderby('fech_ini_hor','asc')
        ->get()->first();

       if($permiso != NULL){
               $salida = FormatoPermisos::where('folio_per', $id)->update(['firmado' => 1]);
        }
     //  return view('formatopermisos.seguridad');
       /* if($permiso->firmado == 1){
            return view('formatopermisos.seguridad');
        }else{*/
            return view('formatopermisos.firma2', compact('folio'));
        //}
        
    }

    public function permisoempleado(Request $request){
        $hoy=date('Y-m-d');
        
        $permiso = FormatoPermisos::where('status','APLICADO')
        ->where('fk_no_empleado',$request->no_empleado)
        ->where('fech_ini_per',$hoy)
        ->orderby('fech_ini_hor','asc')
        ->get();
        return view('formatoPermisos.seguridad', compact('permiso') );
    }

    public function seguridad(Request $request){
        return view('formatoPermisos.seguridad');
        $permiso = FormatoPermisos::where('fecha_solicitud', 'NULL')
        ->get();
        return view('formatoPermisos.seguridad', compact('permiso') );
    }

    public function form2(){
        if(auth()->user()->hasRole('Jefe Administrativo')){
            $modulo='';  
       }else{
            $obtener_modulo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->select('Modulo')->get()->first();

            if(isset($obtener_modulo)){
                $modulo = $obtener_modulo->Modulo;
            }else{
                $modulo="";
            }
       }

       $parametros = Parametros::WHERE('Status','=','A')->where('modulo','2')->get();
       $repetido=0;
             
        if($modulo=='' && !auth()->user()->hasRole('Gerente Produccion') && !auth()->user()->hasRole("Seguridad e Higiene")){

            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo')
           ->where('permisos.jefe_directo',auth()->user()->no_empleado)
           ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

        }else{
            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','dias')
            ->where('Modulo',$modulo)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();
            
        }

      /*  if(auth()->user()->hasRole('Jefe Administrativo') || auth()->user()->hasRole('Vicepresidente')){
            return view('FormatoPermisos.index', compact('permisos','cont','parametros', 'repetido'));
        }else{
            if(auth()->user()->hasRole('Team Leader') || auth()->user()->hasRole('Gerente Produccion') || auth()->user()->hasRole("Coordinador/Analista") || auth()->user()->hasRole("Seguridad e Higiene")){
                */
                return view('formatopermisos.form2');
           /* }else{
                return redirect('/formatopermisos.solicitar');
            }
        }    */
    }

    public function excepcion(){
        if(auth()->user()->hasRole('Jefe Administrativo')){
            $modulo='';  
       }else{
            $obtener_modulo = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->select('Modulo')->get()->first();

            if(isset($obtener_modulo)){
                $modulo = $obtener_modulo->Modulo;
            }else{
                $modulo="";
            }
       }

       $parametros = Parametros::WHERE('Status','=','A')->where('modulo','2')->get();
       $repetido=0;
             
        if($modulo=='' && !auth()->user()->hasRole('Gerente Produccion') && !auth()->user()->hasRole("Seguridad e Higiene")){

            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo')
           ->where('permisos.jefe_directo',auth()->user()->no_empleado)
           ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();

        }else{
            $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
            ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
            ->select 
            ('folio_per','fecha_solicitud','fech_ini_per','fech_fin_per','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','dias')
            ->where('Modulo',$modulo)
            ->orderby('fecha_solicitud','desc')
            ->get();
            $cont = $this->setUnique();
            
        }

      /*  if(auth()->user()->hasRole('Jefe Administrativo') || auth()->user()->hasRole('Vicepresidente')){
            return view('FormatoPermisos.index', compact('permisos','cont','parametros', 'repetido'));
        }else{
            if(auth()->user()->hasRole('Team Leader') || auth()->user()->hasRole('Gerente Produccion') || auth()->user()->hasRole("Coordinador/Analista") || auth()->user()->hasRole("Seguridad e Higiene")){
                */
                return view('formatopermisos.form');
           /* }else{
                return redirect('/formatopermisos.solicitar');
            }
        }    */

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

        //return FormatoPermisos::where('folio_per','=',$request->input('folio_per'))->count();
        return FormatoPermisos::all()->count();

    }


    public function viewAjax3(Request $request){
        
        $fech = $request->fecha;
        $modulo = $request->modulo;
        $departamento = $request->departamento;

        if($modulo==''){
            
            $datos1 = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_permisos','cat_permisos.id_permiso','=','permisos.tipo_per')
            ->where('fech_ini_per','<=',$fech)->where('fech_fin_per','>=',$fech)->where('Tbl_Empleados_SIA.Departamento',$departamento)->where('cat_permisos.ausentismo',1)->get()->count();

            $datos2 = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','vacaciones2.fk_no_empleado')
            ->join('cat_permisos','cat_permisos.id_permiso','=','permisos.tipo_per')
            ->where('fech_ini_vac','<=',$fech)->where('fech_fin_vac','>=',$fech)->where('Tbl_Empleados_SIA.Departamento',$departamento)->where('cat_permisos.ausentismo',1)->get()->count();
        }else{
            $datos1 = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
            ->join('cat_permisos','cat_permisos.id_permiso','=','permisos.tipo_per')
        ->where('fech_ini_per','<=',$fech)->where('fech_fin_per','>=',$fech)->where('Tbl_Empleados_SIA.Modulo',$modulo)->where('cat_permisos.ausentismo',1)->get()->count();
        
            $datos2 = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','vacaciones2.fk_no_empleado')
            ->join('cat_permisos','cat_permisos.id_permiso','=','permisos.tipo_per')
        ->where('fech_ini_vac','<=',$fech)->where('fech_fin_vac','>=',$fech)->where('Tbl_Empleados_SIA.Modulo',$modulo)->where('cat_permisos.ausentismo',1)->get()->count();
        }

        if($datos1 > 0 || $datos2 >0){
            $datos=1;
        }else{
            $datos=0;
        }
        return $datos;
        //return 'holaa'; 

    }
    


}