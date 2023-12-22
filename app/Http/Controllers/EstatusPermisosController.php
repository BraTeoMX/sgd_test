<?php

namespace App\Http\Controllers;

use App\FormatoPermisos;
use App\Vacaciones;
use App\RegistroIncidencias;
use App\Mail\SolicitudAprobada;
use App\Mail\SolicitudAprobadaPermisos;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

use Laracasts\Flash\Flash;
use JsValidator;
use PDF;

class EstatusPermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inicio= ($request->filled('inicio_fecha')) ? $request->inicio_fecha : date('Y-m-d');
        $fin= ($request->filled('fin_fecha')) ? $request->fin_fecha : date('Y-m-d');

        $registroincidencias = Registroincidencias::where('actualizado','<>',1)->where('status','<>','')->get();

        foreach($registroincidencias as $registro){

            if($registro->tipo == 1){
                Vacaciones::WHERE('folio_vac', $registro->folio)->UPDATE(['permiso_jefe' => 1, 'status' => $registro->status, 'fecha_aprobacion' => date($registro->updated_at), 'excepcion' => 0]);
                RegistroIncidencias::WHERE('folio', $registro->folio)->UPDATE(['actualizado' => 1, 'enviado' => 1]);

                $vacaciones = Vacaciones::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'vacaciones2.fk_no_empleado')
                ->select('folio_vac', 'fecha_solicitud', 'fech_ini_vac', 'fech_fin_vac', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Puesto', 'Departamento', 'eventualidades', 'periodos') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
                ->where('folio_vac', '=', $registro->folio)->where('status_Emp', 'A')
                ->get();

                $correo = Vacaciones::join('users', 'users.no_empleado', '=', 'vacaciones2.fk_no_empleado')
                ->select('email')->WHERE('folio_vac', '=', $registro->folio)->first();
                //dd($correo->email);
                  Mail::to($correo->email)
              //  Mail::to('gvergara@intimark.com.mx')
                ->send(new SolicitudAprobada($vacaciones));  //Enviar correo de asolicitud aprobada
            }else{
                FormatoPermisos::WHERE('folio_per', $registro->folio)->UPDATE(['permiso_jefe' => 1, 'status' => $registro->status, 'fecha_aprobacion' => date($registro->updated_at), 'excepcion' => 0]);
                RegistroIncidencias::WHERE('folio', $registro->folio)->UPDATE(['actualizado' => 1, 'enviado' => 1]);

                $permisos = FormatoPermisos::join('Tbl_Empleados_SIA', 'Tbl_Empleados_SIA.No_Empleado', '=', 'permisos.fk_no_empleado')
                ->select('folio_per', 'fecha_solicitud', 'fech_ini_per', 'fech_fin_per', 'No_Empleado', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Puesto', 'Departamento') //QUERY TRAER EL CORREO DEL TRABAJOR Y MANDARLE EL CORREO
                ->where('folio_per', '=', $registro->folio)->where('status_Emp', 'A')
                ->get();

                $correo = FormatoPermisos::join('users', 'users.no_empleado', '=', 'permisos.fk_no_empleado')
                ->select('email')->WHERE('folio_per', '=', $registro->folio)->first();

              // dd($correo->email);
                  Mail::to($correo->email)
               // Mail::to('gvergara@intimark.com.mx')
                ->send(new SolicitudAprobadaPermisos($permisos));  //Enviar correo de asolicitud aprobada
            }
        }

        if(auth()->user()->hasRole("Team Leader")){

            if($inicio != $fin){
                $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->join('cat_permisos','permisos.tipo_per','=','cat_permisos.id_permiso')
                ->select
                ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','Modulo','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','permisos.documento','cat_permisos.permiso','firmado','excepcion','autorizado_por','permisos.updated_at')
                ->where('fech_ini_per', '>=', $inicio)->where('fech_fin_per', '<=', $fin)
                ->orderby('fech_ini_per','desc')
                ->orderby('Nom_Emp')
                //->simplePaginate(10);
                ->get();
            }else{

                $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->join('cat_permisos','permisos.tipo_per','=','cat_permisos.id_permiso')
                ->select
                ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','permisos.documento','cat_permisos.permiso','firmado','excepcion','autorizado_por','permisos.updated_at')
                ->where('fech_ini_per', '=', $inicio)
                ->orderby('fech_ini_per','desc')
                ->orderby('Nom_Emp')
                //->simplePaginate(10);
                ->get();
            }
        }else{
            if($inicio != $fin){
                $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->join('cat_permisos','permisos.tipo_per','=','cat_permisos.id_permiso')
                ->select
                ('folio_per','tipo_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','permisos.documento','cat_permisos.permiso','firmado','excepcion','autorizado_por','permisos.updated_at')
                ->where('fech_ini_per', '>=', $inicio)->where('fech_fin_per', '<=', $fin)
                ->orderby('fech_ini_per','desc')
                ->orderby('Nom_Emp')
                //->simplePaginate(10);
                ->get();
            }else{
                $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->join('cat_permisos','permisos.tipo_per','=','cat_permisos.id_permiso')
                ->select
                ('folio_per','tipo_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','permisos.documento','cat_permisos.permiso','firmado','excepcion','autorizado_por','permisos.updated_at')
                ->where('fech_ini_per', '=', $inicio)
                ->orderby('fech_ini_per','desc')
                ->orderby('Nom_Emp')
                //->simplePaginate(10);
                ->get();
            }
        }
        return view('estatuspermisos.index', compact('permisos','inicio','fin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function anexardocumento($folio)
    {

        return view('documentopermisos.index', compact('folio'));
    }
}
