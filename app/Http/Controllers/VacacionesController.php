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
use App\RegistroIncidencias;
use App\Vigenciavacaciones;



use App\Calendario;

use App\Imports\VacacionesmasivasImport;

use DB;

use App\Mail\SolicitudVacaciones;
use App\Mail\SolicitudVacacionesVP;


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

            'no_empleado' => 'required',
            'nombre' => 'required',

        ];

        $this->attributeNames = [

            'no_empleado' => 'categoria',
            'nombre' => 'descripcion',

        ];

        $this->errorMessages = [

            'required' => 'El campo :attribute es requerido',

        ];

        $this->model = $model;
    }

    /*public function index(Request $request)*/
    public function index()
    {

        $jefe = Tbl_Empleado_SIA::where('No_Empleado', str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT))->where('Status_Emp', 'A')
            ->get()->first();

        if (auth()->user()->hasRole('Jefe Administrativo')) {
            $modulo = '';
        } else {
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->select('Modulo')->get()->first();

            if (isset($obtener_modulo)) {
                $modulo = $obtener_modulo->Modulo;
            } else {
                $modulo = "";
            }
        }


        $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();
        $repetido = 0;

        if ($modulo == '' && !auth()->user()->hasRole('Gerente Produccion') && !auth()->user()->hasRole("Seguridad e Higiene")) {

            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
                ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
                ->select('folio_vac', 'fecha_solicitud', 'fecha_aprobacion', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'eventualidades', 'periodos')
                ->where('vacaciones2.jefe_directo', $jefe->No_Empleado)
                ->orderby('fecha_solicitud', 'desc')
                ->get();
            $cont = $this->setUnique();
        } else {
            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
                ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
                ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'dias_solicitud', 'eventualidades', 'periodos')
                ->where('Modulo', $modulo)
                ->orderby('fecha_solicitud', 'desc')
                ->get();
            $cont = $this->setUnique();
        }

        if (auth()->user()->hasRole('Jefe Administrativo')) {
            return view('vacaciones.index', compact('vacaciones', 'cont', 'parametros', 'repetido'));
        } else {
            if (auth()->user()->hasRole('Team Leader') || auth()->user()->hasRole('Gerente Produccion') || auth()->user()->hasRole("Coordinador/Analista") || auth()->user()->hasRole("Seguridad e Higiene") || auth()->user()->hasRole('Vicepresidente')) {
                return view('vacaciones.form');
            } else {
                return redirect('/vacaciones.solicitarvacaciones');
            }
        }
    }


    public function create()
    {
    }

    public function store(Request $reg_sol_vac)
    {

        $vac = new Vacaciones;
        $vac->folio_vac = $this->getfolio($reg_sol_vac->id_planta);
        $vac->fecha_solicitud = date('Y-m-d');
        $vac->fk_no_empleado = $reg_sol_vac->no_empleado;
        $vac->dias_solicitud = $reg_sol_vac->dias_laborales;
        $vac->fech_ini_vac = $reg_sol_vac->inicio_vac;

        $existe = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', 'Vacaciones2.fk_no_empleado') //soslicitud pendiente sin respuesta
            ->where("fech_ini_vac", "<=", $reg_sol_vac->inicio_vac)
            ->where("fech_fin_vac", ">=", $reg_sol_vac->inicio_vac)
            ->where('modulo', $reg_sol_vac->modulo_emp)
            ->select('folio_vac')->get()->first();

        $repetido = 0;

        if ($existe) {
            $repetido = 1;
        } else {
            $repetido = 0;
        }


        if ($reg_sol_vac->fin_vac == null) {
            $vac->fech_fin_vac = $reg_sol_vac->inicio_vac;
        } else {
            $vac->fech_fin_vac = $reg_sol_vac->fin_vac;
        }

        if ($reg_sol_vac->eventualidad == 1) {
            $vac->eventualidades = '1';
         //   $data = array('no_empleado' => $vac->fk_no_empleado, 'eventualidad' => '1', 'periodo' => '0', 'anio' => '2023', 'fecha' => $reg_sol_vac->inicio_vac);
            $data = array('no_empleado' => $vac->fk_no_empleado, 'eventualidad' => '1', 'periodo' => '0', 'anio' => '2023', 'estatus' => 'APLICADO', 'fecha' => date('Y-m-d'),'inicial' => $reg_sol_vac->inicio_vac, 'final' => $reg_sol_vac->fin_vac);
      //adecuacion vacaciones 18 meses 2023
            DB::table('eventualidad_periodo')->insert($data);
        } else {
            $vac->periodos = '1';
            $data = array('no_empleado' => $vac->fk_no_empleado, 'eventualidad' => '0', 'periodo' => '1', 'anio' => '2023', 'estatus' => 'APLICADO', 'fecha' => date('Y-m-d'), 'inicial' => $reg_sol_vac->inicio_vac, 'final' => $reg_sol_vac->fin_vac);
//adecuacion vacaciones 18 meses 2023
//            $data = array('no_empleado' => $vac->fk_no_empleado, 'eventualidad' => '0', 'periodo' => '1', 'anio' => '2023', 'fecha' => $reg_sol_vac->inicio_vac);
            DB::table('eventualidad_periodo')->insert($data);
        }

        if ($reg_sol_vac->idjefe == null) {
            $obtener_jefe = Tbl_Empleado_SIA::where('No_Empleado', str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT))->where('Status_Emp', 'A')
                ->select('Puesto', 'Email_Emp')->get()->first();
            $vac->jefe_directo = $obtener_jefe->Puesto;
        } else {
            $obtener_jefe2 = Tbl_Empleado_SIA::where('No_Empleado', str_pad($reg_sol_vac->idjefe, 7, "0", STR_PAD_LEFT))->where('Status_Emp', 'A')
                ->get();
            $vac->jefe_directo = $obtener_jefe2[0]->Puesto;
        }
        if ($repetido == 0) {
            $vac->status = 'PENDIENTE';
        } else {
            $vac->status = 'CANCELADO';
        EventualidadPeriodo::WHERE('folio', $vac->folio_vac)->UPDATE(['estatus' => 'CANCELADO'/*, 'autorizado_por' => auth()->user()->name*/]);
        }

        $vac->save();

        $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', 'Vacaciones2.fk_no_empleado')->where('Vacaciones2.fk_no_empleado', $reg_sol_vac->no_empleado)
            ->select('Modulo')->get()->first();

        $modulo = $obtener_modulo->Modulo;
        $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();


        if ($modulo != '') {
            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
                ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
                ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'dias_solicitud', 'eventualidades', 'periodos')
                ->where('Modulo', $modulo)
                ->orderby('fecha_solicitud', 'desc')
                ->get();
            $cont = $this->setUnique();
        } else {

            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
                ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
                ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'dias_solicitud', 'eventualidades', 'periodos')
                ->where('Vacaciones2.folio_vac', $vac->folio_vac)
                ->orderby('fecha_solicitud', 'desc')
                ->get();
            $cont = $this->setUnique();
        }
        $vac_email = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Puesto', 'Departamento', 'eventualidades', 'periodos', 'status') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
            ->where('folio_vac', $vac->folio_vac)->where('status_Emp', 'A')
            ->get();



        $vac_nivel = Tbl_Empleado_SIA::join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
            ->where('No_Empleado',$reg_sol_vac->idjefe)->where('Status_Emp', 'A')
            ->select('Nivel')->get()->first();

        if ((auth()->user()->hasRole('Team Leader') ||  auth()->user()->hasRole('Gerente Produccion')) || auth()->user()->hasRole("Seguridad e Higiene") and $modulo != '') {
            return view('vacaciones.index', compact('vacaciones', 'cont', 'parametros', 'repetido'));
        } else {
            if (auth()->user()->hasRole('Vicepresidente') || auth()->user()->hasRole('Coordinador/Analista') || auth()->user()->hasRole("Seguridad e Higiene")) {
                return view('vacaciones.index', compact('vacaciones', 'cont', 'parametros', 'repetido'));
            } else {

                $correo = User::where('no_empleado', ltrim($reg_sol_vac->idjefe, "0"))
                    ->select('email')->get()->first();
                 //   dd($vac_nivel->Nivel);
                if ($vac_nivel->Nivel == 1) {

                    $registroincidencia = new RegistroIncidencias();
                    $registroincidencia->create([
                        'folio' => $vac->folio_vac,
                        'tipo' => '1',
                    ]);

                    //dd($correo->email);

                    Mail::to($correo->email) // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
                    //   Mail::to('gvergara@intimark.com.mx') // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
                        ->send(new SolicitudVacacionesVP($vac_email));
                } else {
                      // dd($correo->email);

                   Mail::to($correo->email) // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
                      // Mail::to('gvergara@intimark.com.mx') // se tiene que mandar llamar el correo de la persona que autoriza, correo institucional
                        ->send(new SolicitudVacaciones($vac_email));
                }
                return redirect('/home');
            }
        }
    }

    public function getVacaciones()
    {
        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.ID_Empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Puesto', 'Departamento', 'Modulo', 'Status_Emp', 'eventualidades', 'periodos')
            ->orwherenull('permiso_jefe')
            ->get();
        return $vacaciones;
    }

    public function getfolio($planta)
    {
        $anio = date("Y");
        $ult_vac = Vacaciones::orderByDesc('IdVacaciones')->get()->first();
        if ($ult_vac == NULL) {
            $ult_vac = 1;
        } else {
            $ult_vac = $ult_vac->IdVacaciones + 1;
        }
        $ult_vac = str_pad($ult_vac, 4, 0, STR_PAD_LEFT);
        if ($planta == 'Intimark1') {
            $planta = 'A';
        } else {
            $planta = 'B';
        }
        $folio = $anio . $ult_vac . $planta;
        return $folio;
    }

    public function liberar($folio)
    {

        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Puesto', 'Departamento', 'eventualidades', 'periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
            ->where('folio_vac', '=', $folio)
            ->get();

        return view('vacaciones.liberar', compact('vacaciones'));
    }
    public function liberarPermiso($folio)
    {


        $correo = Vacaciones::join('users', 'users.no_empleado', '=', 'vacaciones2.fk_no_empleado')
            ->select('email')->WHERE('folio_vac', '=', $folio)->first();

        if (auth()->user()->hasRole("Vicepresidente")) {
            Vacaciones::WHERE('folio_vac', $folio)->UPDATE(['permiso_jefe' => 1, 'status' => 'APLICADO', 'fecha_aprobacion' => date("Y-m-d"), 'excepcion' => 1, 'autorizado_por' => auth()->user()->name]);
        } else {
            Vacaciones::WHERE('folio_vac', $folio)->UPDATE(['permiso_jefe' => 1, 'status' => 'APLICADO', 'fecha_aprobacion' => date("Y-m-d"), 'excepcion' => 0, 'autorizado_por' => auth()->user()->name]);
        }

        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Puesto', 'Departamento', 'eventualidades', 'periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
            ->where('folio_vac', '=', $folio)->where('status_Emp', 'A')
            ->get();

        if (auth()->user()->hasRole('Jefe Administrativo')) {

            Mail::to($correo->email) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional

                //     Mail::to('gvergara@intimark.com.mx')
                ->send(new SolicitudAprobada($vacaciones));  //Enviar correo de asolicitud aprobada
        }


        $vacaciones = $this->getVacaciones();

        $this->setMessage('Solicitud Aprobada');
        $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();


        if (auth()->user()->hasRole("Team Leader") || auth()->user()->hasRole('Jefe Administrativo')) {
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->select('Modulo')->orderby('Modulo', 'desc')->get()->first();

            $modulo = $obtener_modulo->Modulo;

            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
                ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
                ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'dias_solicitud', 'eventualidades', 'periodos')
                ->where('Modulo', $modulo)
                ->where('Email_Emp', auth()->user()->email)
                ->orderby('fecha_solicitud', 'desc')
                ->get();
            $cont = $this->setUnique();

            //return view('vacaciones.index', compact('vacaciones','cont','parametros'));
            return redirect('/vacaciones');
        } else {
            return redirect('/vacaciones');
        }
    }

    public function liberarPermiso2($folio)
    {


        $correo = Vacaciones::join('users', 'users.no_empleado', '=', 'vacaciones2.fk_no_empleado')
            ->select('email')->WHERE('folio_vac', '=', $folio)->first();

        Vacaciones::WHERE('folio_vac', $folio)->UPDATE(['permiso_jefe' => 1, 'status' => 'APLICADO', 'fecha_aprobacion' => date("Y-m-d"), 'autorizado_por' => '']);

        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'vacaciones2.fk_no_empleado')
            ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Puesto', 'Departamento', 'eventualidades', 'periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
            ->where('folio_vac', '=', $folio)->where('status_Emp', 'A')
            ->get();
/**********vacaciones 18 meses 2023 */
            $saldos = Vacaciones::where('folio_vac', '=', $folio)->get()->first();

            $vigencias = Vigenciavacaciones::where('no_trabajador', $saldos->fk_no_empleado)->get()->first();

            if($vigencias->saldo_anterior > 0 and $vigencias->saldo_anterior<= $saldos->dias_solicitud){
                $saldo_anterior = 0;
                $saldo_nuevo = $vigencias->saldo_nuevo-($saldos->dias_solicitud-$vigencias->saldo_anterior);
            }else{
                if($vigencias->saldo_anterior <=0){
                    $saldo_anterior = $vigencias->saldo_anterior;
                    $saldo_nuevo = $vigencias->saldo_nuevo-$saldos->dias_solicitud;
                }else{
                    $saldo_anterior = $vigencias->saldo_anterior-$saldos->dias_solicitud;
                    $saldo_nuevo = $vigencias->saldo_nuevo;
                }
            }

            Vigenciavacaciones::WHERE('no_trabajador', $saldos->fk_no_empleado)->UPDATE(['saldo_anterior' => $saldo_anterior, 'saldo_nuevo' => $saldo_nuevo, 'updated_at' => date("Y-m-d")]);

/**************************** */
        Mail::to($correo->email) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional

            //dd($correo->email);
            //  Mail::to('gvergara@intimark.com.mx')
            ->send(new SolicitudAprobada($vacaciones));


        $vacaciones = $this->getVacaciones();

        $this->setMessage('Solicitud Aprobada');
        $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();

        return redirect('/home');
    }

    public function denegarPermiso($folio)
    {
        $repetido = 0;
        $correo = Vacaciones::join('users', 'users.no_empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->select('email')->WHERE('folio_vac', '=', $folio)->first();

        if (auth()->user()->hasRole("Vicepresidente")) {
            Vacaciones::WHERE('folio_vac', $folio)->UPDATE(['permiso_jefe' => 1, 'status' => 'CANCELADO', 'fecha_aprobacion' => date("Y-m-d"), 'excepcion' => 1, 'autorizado_por' => auth()->user()->name]);
        } else {
            Vacaciones::WHERE('folio_vac', $folio)->UPDATE(['permiso_jefe' => 1, 'status' => 'CANCELADO', 'fecha_aprobacion' => date("Y-m-d"), 'excepcion' => 0,  'autorizado_por' => auth()->user()->name]);
        }

        $vacaciones = $this->getVacaciones();

        $this->setMessage('Solicitud Denegada');

        if (auth()->user()->hasRole("Team Leader") || auth()->user()->hasRole('Jefe Administrativo')) {
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->select('Modulo')->orderby('Modulo', 'desc')->get()->first();

            $modulo = $obtener_modulo->Modulo;
            $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();

            //$jefe = Tbl_Empleado_SIA::select('No_Empleado')->where('email_Emp',auth()->user()->email)->where('status_Emp','A')->get()->first();
            $jefe = Tbl_Empleado_SIA::where('No_Empleado', str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT))->where('Status_Emp', 'A')
                ->get()->first();

            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
                ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
                ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'dias_solicitud', 'eventualidades', 'periodos')
                ->where('Modulo', $modulo)
                ->where('vacaciones2.jefe_directo', $jefe->No_Empleado)
                ->orderby('fecha_solicitud', 'desc')
                ->get();
            $cont = $this->setUnique();

            return view('vacaciones.index', compact('vacaciones', 'cont', 'parametros', 'repetido'));
        } else {
            return redirect('/vacaciones');
        }
    }

    public function denegarPermiso2($folio)
    {


        $correo = Vacaciones::join('users', 'users.no_empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->select('email')->WHERE('folio_vac', '=', $folio)->first();

        Vacaciones::WHERE('folio_vac', $folio)->UPDATE(['permiso_jefe' => 1, 'status' => 'CANCELADO', 'fecha_aprobacion' => date("Y-m-d"),  'autorizado_por' => auth()->user()->name]);

        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Puesto', 'Departamento', 'eventualidades', 'periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
            ->where('folio_vac', '=', $folio)->where('status_Emp', 'A')
            ->get();
        if (isset($correo->email)) {

            Mail::to($correo->email) // se tiene que mandar llamar el correo del solicitante $correo->Email_Emp, se cambio correo personal a institucional
                // Mail::to('gvergara@intimark.com.mx')
                ->send(new SolicitudDenegada($vacaciones));
        }


        $vacaciones = $this->getVacaciones();

        $this->setMessage('Solicitud Denegada');

        return redirect('/vacaciones');
    }

    public function enviarCorreo()
    {
    }

    public function vac_liberadas()
    {
        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
            ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
            ->select('folio_vac', 'fecha_solicitud', 'fecha_aprobacion', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'eventualidades', 'periodos')

            ->orderby('fecha_solicitud', 'desc')
            ->get();
        $cont = $this->setUnique();

        return view('vacaciones.vacaciones_liberadas', compact('vacaciones', 'cont'));
    }

    public function cancelacion()
    {
        //$jefe = Tbl_Empleado_SIA::select('No_Empleado')->where('email_Emp',auth()->user()->email)->where('status_Emp','A')->get()->first();
        $jefe = Tbl_Empleado_SIA::where('No_Empleado', str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT))->where('Status_Emp', 'A')
            ->get()->first();

        if (auth()->user()->hasRole('Jefe Administrativo')) {
            $modulo = '';
        } else {
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->select('Modulo')->get()->first();

            if (isset($obtener_modulo)) {
                $modulo = $obtener_modulo->Modulo;
            } else {
                $modulo = "";
            }
        }

        $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();
        $repetido = 0;



        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
            ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
            ->select('folio_vac', 'fecha_solicitud', 'fecha_aprobacion', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'eventualidades', 'periodos')
            ->where('vacaciones2.status', '<>', 'CANCELADO')
            ->orderby('fech_ini_vac', 'desc')
            ->get();
        $cont = $this->setUnique();

        return view('vacaciones.cancelacion', compact('vacaciones', 'cont', 'parametros', 'repetido'));
    }

    public function show(Request $noEmpleado)
    {
    }

    public function buscarEmp(Request $noEmpleado)
    {
    }

    public function edit(Request $categoria)
    {
    }

    public function update2($folio)
    {
        $repetido = 0;
        Vacaciones::where('folio_vac', $folio)
            ->update([
                'status' => 'CANCELADO', 'fecha_aprobacion' => date("Y-m-d"),
                'usuario_actualizacion_id' => auth()->user()->id,  'autorizado_por' => auth()->user()->name
            ]);
        EventualidadPeriodo::WHERE('folio', $folio)->UPDATE(['estatus' => 'CANCELADO'/*, 'autorizado_por' => auth()->user()->name*/]);

        //$jefe = Tbl_Empleado_SIA::select('No_Empleado')->where('email_Emp',auth()->user()->email)->where('status_Emp','A')->get()->first();
        $jefe = Tbl_Empleado_SIA::where('No_Empleado', str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT))->where('Status_Emp', 'A')
            ->get()->first();

        if (auth()->user()->hasRole('Jefe Administrativo')) {
            $modulo = '';
        } else {
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->select('Modulo')->get()->first();

            if (isset($obtener_modulo)) {
                $modulo = $obtener_modulo->Modulo;
            } else {
                $modulo = "";
            }
        }

        $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();
        $repetido = 0;


        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
            ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
            ->select('folio_vac', 'fecha_solicitud', 'fecha_aprobacion', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'eventualidades', 'periodos')
            ->where('vacaciones2.status', '<>', 'CANCELADO')
            ->orderby('fecha_solicitud', 'desc')
            ->get();
        $cont = $this->setUnique();

        return view('vacaciones.cancelacion', compact('vacaciones', 'cont', 'parametros', 'repetido'));
        // return redirect('/vacaciones');
    }

    public function update($folio)
    {
        $repetido = 0;
        Vacaciones::where('folio_vac', $folio)
            ->update([
                'status' => 'CANCELADO', 'fecha_aprobacion' => date("Y-m-d"),
                'usuario_actualizacion_id' => auth()->user()->id,  'autorizado_por' => auth()->user()->name
            ]);
        EventualidadPeriodo::WHERE('folio', $folio)->UPDATE(['estatus' => 'CANCELADO' /*,  'autorizado_por' => auth()->user()->name*/]);

        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Puesto', 'Departamento', 'eventualidades', 'periodos', 'status') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
            ->where('folio_vac', '=', $folio)->where('status_Emp', 'A')
            ->get();

        if (auth()->user()->hasRole("Team Leader") || auth()->user()->hasRole('Jefe Administrativo')) {
            $obtener_modulo = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->select('Modulo')->orderby('Modulo', 'desc')->get()->first();

            $modulo = $obtener_modulo->Modulo;
            $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();


            $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
                ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
                ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'dias_solicitud', 'eventualidades', 'periodos')
                ->where('Modulo', $modulo)
                ->orderby('fecha_solicitud', 'desc')
                ->get();
            $cont = $this->setUnique();

            return view('vacaciones.index', compact('vacaciones', 'cont', 'parametros', 'repetido'));
        } else {

            return redirect('/vacaciones');
        }
    }


    public function destroy(Request $folio)
    {
    }

    public function form2()
    {
        return view('vacaciones.form2');
    }


    public function autorizar(Request $request)
    {
        $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();
        $repetido = 0;

        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
            ->join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
            ->join('cat_departamentos', 'Tbl_Empleados_SIA.Departamento', '=', 'cat_departamentos.id_Departamento')
            ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'cat_puestos.Puesto', 'cat_departamentos.Departamento', 'Vacaciones2.status', 'fk_no_empleado', 'jefe_directo', 'Modulo', 'dias_solicitud', 'eventualidades', 'periodos')
            ->where('vacaciones2.status', 'Pendiente')
            ->where('Vacaciones2.fk_no_empleado', $request->noEmpleado)
            ->orderby('fecha_solicitud', 'desc')
            ->get();
        $cont = $this->setUnique();


        return view('vacaciones.index', compact('vacaciones', 'cont', 'parametros', 'repetido'));
    }

    public function solicitarvacaciones()
    {

        $email = auth()->user()->email;

        date_default_timezone_set('America/Mexico_City');

        $fechaActual = date('d-m-Y');
        $fechaSegundos = strtotime($fechaActual);
        $semana = date('W', $fechaSegundos);

       /* if ($semana > 50) { //se elimina esta arregla a peticion de Lic. Virgilio 14/12/2023
            echo "<script type='text/javascript'>
            alert('Por cierre Anual, ya no se permiten generar Solicitudes de Vacaciones');
            window.location.href='/home';
            </script>";
        }*/

        $no_empleado = str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT);;

        $tag = str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT);;

        $datosEmpleado = Tbl_Empleado_SIA::WHERE('Status_Emp', '=', 'A')->WHERE('No_Empleado', '=', $tag)
            ->orWHERE('Status_Emp', '=', 'A')->WHERE('No_TAG', '=', $tag)
            ->get();

        $getdep = Tbl_Empleado_SIA::WHERE('Status_Emp', '=', 'A')->WHERE('No_Empleado', '=', $tag)
            ->orWHERE('Status_Emp', '=', 'A')->WHERE('No_TAG', '=', $tag)
            ->get()->first();

        if (isset($getdep)) {

            $dep = $getdep->Departamento;
            $puesto = $getdep->Puesto;
            $cveci2 = $getdep->cveci2;


            $anio = date('Y');
            $mes = date('m');
            $dia = date('d');


            $anio2 = strtotime('+1 year', strtotime($anio)); //Se añade un año mas
            $anio2 = date('Y', $anio2);

            $anio3 = strtotime('-1 year', strtotime($anio)); //Se añade un año mas
            $anio3 = date('Y', $anio3);

            $datos = explode("-", $getdep->Fecha_In);
            $anio_antiguedad = $datos[0];
            $mes_antiguedad = $datos[1];
            $dia_antiguedad = $datos[2];
            $antiguedad = $anio . '-' . $mes_antiguedad . '-' . $dia_antiguedad;

            if ($antiguedad <= $anio . '-' . $mes . '-' . $dia and $antiguedad >= $anio . '-01-01') {
                $antiguedad2 = $anio2 . '-' . $mes_antiguedad . '-' . $dia_antiguedad;


               // $eventualidad = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('fecha', '<=', $antiguedad2)->where('fecha', '>=', $antiguedad)->sum('eventualidad');
               // $periodo = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('fecha', '<=', $antiguedad2)->where('fecha', '>=', $antiguedad)->sum('periodo');
               $eventualidad = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('inicial', '<=', $antiguedad2)->where('inicial', '>=', $antiguedad)->where('estatus','APLICADO')->sum('eventualidad');
                $periodo = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('inicial', '<=', $antiguedad2)->where('inicial', '>=', $antiguedad)->where('estatus','APLICADO')->sum('periodo');
             //  $eventualidad = Vacaciones::WHERE('fk_no_empleado', $tag)->where('fech_ini_vac', '<=', $antiguedad2)->where('fech_ini_vac', '>=', $antiguedad)->where('status','APLICADO')->sum('eventualidades');
               //$periodo = Vacaciones::WHERE('fk_no_empleado', $tag)->where('fech_ini_vac', '<=', $antiguedad2)->where('fech_ini_vac', '>=', $antiguedad)->where('status','APLICADO')->sum('periodos');
            } else {
                $antiguedad3 = $anio3 . '-' . $mes_antiguedad . '-' . $dia_antiguedad;

                //$eventualidad = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('fecha', '>=', $antiguedad3)->where('fecha', '<=', $antiguedad)->sum('eventualidad');
               // $periodo = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('fecha', '>=', $antiguedad3)->where('fecha', '<=', $antiguedad)->sum('periodo');

               $eventualidad = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('inicial', '>=', $antiguedad3)->where('inicial', '<=', $antiguedad)->where('estatus','APLICADO')->sum('eventualidad');
                $periodo = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('inicial', '>=', $antiguedad3)->where('inicial', '<=', $antiguedad)->where('estatus','APLICADO')->sum('periodo');


              // $eventualidad = Vacaciones::WHERE('fk_no_empleado', $tag)->where('fech_ini_vac', '>=', $antiguedad3)->where('fech_ini_vac', '<=', $antiguedad)->where('status','APLICADO')->sum('eventualidades');
               // $periodo = Vacaciones::WHERE('fk_no_empleado', $tag)->where('fech_ini_vac', '>=', $antiguedad3)->where('fech_ini_vac', '<=', $antiguedad)->where('status','APLICADO')->sum('periodos');
            }

        } else {
            $dep = '';
            $puesto = '';
            $cveci2 = '';
            $antiguedad = '';
            $eventualidad= 0;
            $periodo = 0;
        }

        $puestos = Puestos::WHERE('Status', '=', 'A')->get();

        $departamentos = Departamentos::WHERE('Status', '=', 'A')->get();

        $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();

        $vacaciones = Vacaciones::where('fk_no_empleado', $tag)->where('status', '<>', 'CANCELADO')->get();

        $datosJefe = Tbl_Empleado_SIA::join('permiso_vac', 'Tbl_Empleados_SIA.Puesto', '=', 'permiso_vac.id_jefe')->where('Status_Emp', '=', 'A')
            ->where('id_puesto_solicitante', $cveci2)->where('id_jefe', '<>', $puesto)->orderby('Nom_Emp')->get();

        return view('vacaciones.solicitud_vac', compact('datosEmpleado', 'datosJefe', 'puestos', 'departamentos', 'vacaciones', 'parametros', 'eventualidad', 'periodo'));
    }

    public function solicitarvacaciones2023()
    {

        date_default_timezone_set('America/Mexico_City');

        $fechaActual = date('d-m-Y');
        $fechaSegundos = strtotime($fechaActual);
        $semana = date('W', $fechaSegundos);

        if ($semana > 49) {
            echo "<script type='text/javascript'>
            alert('Por cierre Anual, ya no se permiten generar Solicitudes de Vacaciones');
            window.location.href='/home';
            </script>";
        }

        $no_empleado = str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT);;

        $tag = str_pad(auth()->user()->no_empleado, 7, "0", STR_PAD_LEFT);;

        $datosEmpleado = Tbl_Empleado_SIA::WHERE('Status_Emp', '=', 'A')->WHERE('No_Empleado', '=', $tag)
            ->orWHERE('Status_Emp', '=', 'A')->WHERE('No_TAG', '=', $tag)
            ->get();
        $getdep = Tbl_Empleado_SIA::WHERE('Status_Emp', '=', 'A')->WHERE('No_Empleado', '=', $tag)
            ->orWHERE('Status_Emp', '=', 'A')->WHERE('No_TAG', '=', $tag)
            ->get()->first();

        $vigencia = Vigenciavacaciones::WHERE('no_trabajador', '=', $tag)->get()->first();


        if (isset($getdep)) {

            $dep = $getdep->Departamento;
            $puesto = $getdep->Puesto;
            $cveci2 = $getdep->cveci2;

            $anio = date('Y');
            $mes = date('m');
            $dia = date('d');

            $anio2 = strtotime('+1 year', strtotime($anio)); //Se añade un año mas
            $anio2 = date('Y', $anio2);

            $anio3 = strtotime('-1 year', strtotime($anio)); //Se añade un año mas
            $anio3 = date('Y', $anio3);

            $datos = explode("-", $getdep->Fecha_In);
            $anio_antiguedad = $datos[0];
            $mes_antiguedad = $datos[1];
            $dia_antiguedad = $datos[2];
            $antiguedad = $anio . '-' . $mes_antiguedad . '-' . $dia_antiguedad;
            $antiguedad2 = $anio2 . '-' . $mes_antiguedad . '-' . $dia_antiguedad;
            $antiguedad3 = $anio3 . '-' . $mes_antiguedad . '-' . $dia_antiguedad;


            $vigencia_anterior = date('Y-m-d', strtotime('+6 month', strtotime($antiguedad2)));
            $vigencia_nuevo = date('Y-m-d', strtotime('+1 year', strtotime($vigencia_anterior)));
//ciclo anterior
            $eventualidad1 = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('inicial', '>=', $antiguedad3)->where('inicial', '<=', $antiguedad)->where('estatus','APLICADO')->sum('eventualidad');
            $periodo1 = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('inicial', '>=', $antiguedad3)->where('inicial', '<=', $antiguedad)->where('estatus','APLICADO')->sum('periodo');
//ciclo nuevo
            $eventualidad2 = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('inicial', '>=', $vigencia_anterior)->where('inicial', '<=', $vigencia_nuevo)->where('estatus','APLICADO')->sum('eventualidad');
            $periodo2 = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('inicial', '>=', $vigencia_anterior)->where('inicial', '<=', $vigencia_nuevo)->where('estatus','APLICADO')->sum('periodo');

            $eventualidad_anterior = $eventualidad1;
            $eventualidad_nuevo = $eventualidad2;

            $periodo_anterior = $periodo1;
            $periodo_nuevo = $periodo2;

            $eventualidad3 = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('inicial', '>=', $antiguedad)->where('inicial', '<=', $vigencia_anterior)->where('estatus','APLICADO')->sum('eventualidad');
            $periodo3 = EventualidadPeriodo::WHERE('no_empleado', $tag)->where('inicial', '>=', $antiguedad)->where('inicial', '<=', $vigencia_anterior)->where('estatus','APLICADO')->sum('periodo');
//dd($eventualidad1);
            if ($eventualidad1>3){
                if($vigencia->saldo_anterior >0){
                    if($eventualidad_anterior+$eventualidad1>3){
                        $eventualidad_nuevo = $eventualidad_nuevo + (3-$eventualidad1);
                        $eventualidad_anterior= 3;
                    }else{
                        $eventualidad_anterior= $eventualidad_anterior+$eventualidad1;
                    }
                }else{
                        $eventualidad_nuevo=$eventualidad_nuevo+($eventualidad1-3);
                        $eventualidad_anterior=3;
                }
            }else{
                $eventualidad_anterior=$eventualidad1;
                $eventualidad_nuevo=$eventualidad2;
            }


            if ($periodo1>4){
                if($vigencia->saldo_anterior >0){
                    if($periodo_anterior+$eventualidad1>4){
                        $periodo_nuevo = $periodo_nuevo + (4-$eventualidad1);
                        $periodo_anterior= 4;
                    }else{
                        $periodo_anterior= $periodo_anterior+$eventualidad1;
                    }
                }else{
                        $periodo_nuevo=$periodo_nuevo+($eventualidad1-4);
                        $periodo_anterior=4;
                }
            }else{
                $periodo_anterior=$periodo1;
                $periodo_nuevo=$periodo2;
            }

            if($eventualidad3 > 0){
                if($vigencia->saldo_anterior >0){
                    if($eventualidad_anterior+$eventualidad3>3){
                        $eventualidad_nuevo = $eventualidad_nuevo + (3-$eventualidad_anterior);
                        $eventualidad_anterior= 3;
                    }else{
                        $eventualidad_anterior= $eventualidad_anterior+$eventualidad3;
                    }
                }else{
                        $eventualidad_nuevo=$eventualidad_nuevo+$eventualidad3;
                }
            }else{

            }

            if($periodo3 > 0){
                if($vigencia->saldo_anterior >0){
                    if($periodo_anterior+$eventualidad3>4){
                        $periodo_nuevo = $periodo_nuevo + (3-$periodo_anterior);
                        $periodo_anterior= 4;
                    }else{
                        $periodo_anterior= $periodo_anterior+$periodo3;
                    }
                }else{
                        $periodo_nuevo=$periodo_nuevo+$periodo3;
                }
            }else{

            }


               Vigenciavacaciones::WHERE('no_trabajador', $tag)->UPDATE(['event_anterior' => $eventualidad_anterior, 'periodo_anterior' => $periodo_anterior, 'vigencia_anterior' => $vigencia_anterior, 'event_nuevo' => $eventualidad_nuevo, 'periodo_nuevo' => $periodo_nuevo, 'vigencia_nuevo' => $vigencia_nuevo]);

        } else {
            $dep = '';
            $puesto = '';
            $cveci2 = '';
            $antiguedad = '';
            $eventualidad= 0;
            $periodo = 0;
        }

        $event_anterior=$eventualidad_anterior;
        $event_nuevo=$eventualidad_nuevo;

        $puestos = Puestos::WHERE('Status', '=', 'A')->get();

        $departamentos = Departamentos::WHERE('Status', '=', 'A')->get();

        $parametros = Parametros::WHERE('Status', '=', 'A')->where('modulo', '1')->get();

        $vacaciones = Vacaciones::where('fk_no_empleado', $tag)->where('status', '<>', 'CANCELADO')->get();

        $datosJefe = Tbl_Empleado_SIA::join('permiso_vac', 'Tbl_Empleados_SIA.Puesto', '=', 'permiso_vac.id_jefe')->where('Status_Emp', '=', 'A')
            ->where('id_puesto_solicitante', $cveci2)->where('id_jefe', '<>', $puesto)->orderby('Nom_Emp')->get();


        $fechaEntera = strtotime($vigencia->fecha_aniversario);
        $anio = date("Y", $fechaEntera);
        $mes = date("m", $fechaEntera);
        $dia = date("d", $fechaEntera);

        $fecha_actual = date("d-m-Y");
        $anio2 = date("Y",strtotime($fecha_actual."+ 1 year"));
        $anio3 = date("Y",strtotime($fecha_actual."- 1 year"));
        $anio4 = date("Y",strtotime($fecha_actual."+ 2 year"));

        if($mes < date("m"))  {
                    $periodos1 = $dia.'-'.$mes.'-'.date("Y");
                    $periodos2 = $dia.'-'.$mes.'-'.$anio2;
                    $periodos3 = $dia.'-'.$mes.'-'.$anio2;
                    $periodos4 = $dia.'-'.$mes.'-'.$anio4;
        }else{
            if($mes == date("m")){
                if($dia < date("d")){
                    $periodos1 = $dia.'-'.$mes.'-'.date("Y");
                    $periodos2 = $dia.'-'.$mes.'-'.$anio2;
                    $periodos3 = $dia.'-'.$mes.'-'.$anio2;
                    $periodos4 = $dia.'-'.$mes.'-'.$anio4;
                }else{
                    $periodos1 = $dia.'-'.$mes.'-'.$anio3;
                    $periodos2 = $dia.'-'.$mes.'-'.date("Y");
                    $periodos3 = $dia.'-'.$mes.'-'.date("Y");
                    $periodos4 = $dia.'-'.$mes.'-'.$anio2;
                }
            }else{
                $periodos1 = $dia.'-'.$mes.'-'.$anio3;
                $periodos2 = $dia.'-'.$mes.'-'.date("Y");
                $periodos3 = $dia.'-'.$mes.'-'.date("Y");
                $periodos4 = $dia.'-'.$mes.'-'.$anio2;
            }
        }


        if($fecha_actual>=$vigencia->vigencia_anterior){
            $saldo_anterior=0;

        }else{
            $saldo_anterior=$vigencia->saldo_anterior;
        }
        $event_anterior=$vigencia->event_anterior;
        $periodo_anterior=$vigencia->periodo_anterior;

        if($vigencia->saldo_nuevo == null){
            $saldo_nuevo=0;
            $event_nuevo=0;
            $periodo_nuevo=0;
        }else{
            $saldo_nuevo=$vigencia->saldo_nuevo;
            $event_nuevo=$vigencia->event_nuevo;
            $periodo_nuevo=$vigencia->periodo_nuevo;
        }

        if($event_anterior == null){
            $event_anterior =$eventualidad1;
            $event_nuevo =$eventualidad2;
            $periodo_anterior=$periodo1;
            $periodo_nuevo=$periodo2;
        }

        $vigencia = Vigenciavacaciones::WHERE('no_trabajador', '=', $tag)->get()->first();

     //  dd($event_anterior);
        return view('vacaciones.solicitud_vac2023', compact('datosEmpleado', 'datosJefe', 'puestos', 'departamentos', 'vacaciones', 'parametros','vigencia','periodos1','periodos2','periodos3','periodos4'));
    }

    public function registrar_sol_vac(Request $reg_sol_vac)
    {

        $vac = new Vacaciones;
        $vac->Area_emp = $reg_sol_vac->Departamento;
        $vac->jefe_directo = '1';
        $vac->create($vac);
        Vacaciones::create($reg_sol_vac->all());
        return view('vacaciones.form');
    }



    private function normalizaDatos(Request $request, $id = 0)
    {

        if ($id == 0) {
            $request['usuario_creacion_id'] = auth()->user()->id;
        } else {
            $request['usuario_actualizacion_id'] = auth()->user()->id;
        }

        return $request;
    }



    protected function setValidator(Request $request, $id = 0)
    {

        return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
    }



    private function setQuery($request)
    {

        $query = $this->model;
        if ($request->filled('No_emp')) {
            $query = $query->where('No_emp', '=', $request->input('No_emp'))
                ->where('Status_Emp', '=', 'A');
        }
        return $query;
    }



    //retorno de mensajes en el proyecto

    private function setMessage($mensaje)
    {

        return  flash($mensaje)->success()->important();
    }



    private function setUnique()
    {

        //return Vacaciones::where('folio_vac','=',$request->input('folio_vac'))->count();
        return Vacaciones::all()->count();
    }

    public function viewAjax(Request $request)
    {
        $fech = $request->fecha;
        $pago = $request->pago;

        if ($pago == 'SEMANAL') {
            $frec_pago = 1;
        } else {
            if ($pago == 'QUINCENAL') {
                $frec_pago = 2;
            }
        }
        $nomina = Calendario::where('fecha_calendario', $fech)->get()->first();

        if ($nomina->tipo_nomina == 3) {
            $datos = 1;
        } else {
            $datos = Calendario::where('fecha_calendario', $fech)->where('tipo_nomina', $frec_pago)->get()->count();
        }

        return $datos;
        //return 'holaa';

    }

    public function viewAjax2(Request $request)
    {
        $empleado = $request->empleado;
        $datos = Vacaciones::where('fk_no_empleado', $empleado)->where('status', 'PENDIENTE')->get()->count();
        return $datos;
    }

    public function viewAjax3(Request $request)
    {
        $fech = $request->fecha;
        $modulo = $request->modulo;
        $departamento = $request->departamento;
        $edo_neg = $request->edo_neg;


        if ($modulo == '') {
            $no_empleados = Tbl_Empleado_SIA::where('cveci2', $edo_neg)->where('status_Emp', 'A')->get()->count();

            $parametros = Parametros::select('valor')->where('Status', '=', 'A')->where('clave', 'aus_vac')->where('modulo', '1')->get()->first();

            $porc_ausentismo = $no_empleados * $parametros->valor;

            /*$datos = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->where('Status_Emp', '=', 'A')->where('fech_ini_vac', '<=', $fech)->where('fech_fin_vac', '>=', $fech)->where('Tbl_Empleados_SIA.Departamento', $departamento)->where('status','<>','CANCELADO')->get()->count();*/
            $datos = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->where('Status_Emp', '=', 'A')->where('fech_ini_vac', '<=', $fech)->where('fech_fin_vac', '>=', $fech)->where('Tbl_Empleados_SIA.cveci2', $edo_neg)->where('status','<>','CANCELADO')->get()->count();

            if (ceil($porc_ausentismo) > $datos) {
                $datos = 0;
            } else {
                $datos = 1;
            }
        } else {
            $datos = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'Vacaciones2.fk_no_empleado')
                ->where('Status_Emp', 'A')->where('fech_ini_vac', '<=', $fech)->where('fech_fin_vac', '>=', $fech)->where('Tbl_Empleados_SIA.Modulo', $modulo)->where('status','<>','CANCELADO')->get()->count();
        }

        return $datos;
    }

    public function viewAjax4(Request $request)
    {
        $fech2 = $request->fecha2;
        $fech1 = $request->fecha1;

        $nomina = Calendario::where('fecha_calendario', '>=',$fech1)->where('fecha_calendario','<=',$fech2)->where('tipo',1)->count();
        $datos=$nomina;


        return $datos;


    }


    public function masivas()
    {
        return view('vacaciones.masivas');
    }

    public function importar(Request $request)
    {
        #echo 'hola desde el importarsssssss';
        $import = new VacacionesmasivasImport;
        $file = $request->file('file');
        Excel::import($import, $file);
        return view('vacaciones.masivas')->with('exito', 'ok');
    }

    public function saldo(Request $request)
    {
        return view('vacaciones.saldo');
    }

    public function saldoempleado(Request $request)
    {

        $hoy = date('Y-m-d');
        $tag = substr($request->no_empleado, -9);
        $tag = str_pad($tag, 7, "0", STR_PAD_LEFT);

        $saldo =  Tbl_Empleado_SIA::WHERE('Status_Emp', '=', 'A')->WHERE('No_Empleado', '=', $tag)
            ->orWHERE('Status_Emp', '=', 'A')->WHERE('No_TAG', '=', $tag)
            ->get();

        $vacaciones = Vacaciones::where('fk_no_empleado', $tag)->where('status', '<>', 'CANCELADO')->get();

        return view('vacaciones.saldo', compact('saldo','vacaciones'));
    }
}
