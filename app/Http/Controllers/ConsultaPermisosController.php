<?php

namespace App\Http\Controllers;

use App\FormatoPermisos;
use App\Puestos;
use App\RegistroIncidencias;
use App\Mail\SolicitudAprobada;
use App\Mail\SolicitudAprobadaPermisos;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Mail;

use JsValidator;
use PDF;

class ConsultaPermisosController extends Controller
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
                //Mail::to('gvergara@intimark.com.mx')
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
                ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','Modulo','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','forma','permiso','firmado','excepcion','fech_ini_hor','fech_fin_hor','modo_per','hora_comida','autorizado_por','permisos.updated_at')
                ->where('fech_ini_per', '>=', $inicio)->where('fech_fin_per', '<=', $fin)->where('Tbl_Empleados_SIA.Departamento','29')
                ->orderby('folio_per','desc')
                ->orderby('Nom_Emp')
                //->simplePaginate(10);
                ->get();

            }else{
                $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->join('cat_permisos','permisos.tipo_per','=','cat_permisos.id_permiso')
                ->select
                ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','forma','permiso','firmado','excepcion','fech_ini_hor','fech_fin_hor','modo_per','hora_comida','autorizado_por','permisos.updated_at')
                ->where('fech_ini_per', '=', $inicio)
                ->orderby('folio_per','desc')
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
                ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo','forma','permiso','firmado','excepcion','fech_ini_hor','fech_fin_hor','modo_per','hora_comida','autorizado_por','permisos.updated_at')
                ->where('fech_ini_per', '>=', $inicio)->where('fech_fin_per', '<=', $fin)
                ->orderby('folio_per','desc')
                ->orderby('Nom_Emp')
               // ->simplePaginate(10);
                ->get();


            }else{
                $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->join('cat_permisos','permisos.tipo_per','=','cat_permisos.id_permiso')
                ->select
                ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','permisos.jefe_directo','Modulo','forma','permiso','firmado','excepcion','fech_ini_hor','fech_fin_hor','modo_per','hora_comida','autorizado_por','permisos.updated_at')
                ->where('fech_ini_per', '=', $inicio)
                ->orderby('folio_per','desc')
                ->orderby('Nom_Emp')
                //->simplePaginate(10);
                ->get();


            }
        }

    $jefe = Puestos::WHERE('Status','A')
    ->get();


              return view('consultaPermisos.index', compact('permisos','jefe','inicio','fin'));
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

    public function ticketPDF($id)
    {

        //dd($id);
       // $permisos=FormatoPermisos::find($id);

        $permisos = FormatoPermisos::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','permisos.fk_no_empleado')
        ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id')
        ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
        ->select
        ('folio_per','fecha_solicitud','fecha_aprobacion','fech_ini_per','fech_fin_per','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','permisos.status','fk_no_empleado','jefe_directo','Modulo')
        ->where('folio_per',$id)
        ->orderby('folio_per','desc')
        ->get();

        $totalreg =FormatoPermisos::where('folio_per',$id)
        ->count();
        $largo=215.9;
         if($totalreg>4){
             $largo=(120*$totalreg)+30.2;
         }
        $customPaper = array(0,0,$largo,340.4);
        $mytime = Carbon::now()->toDateTimeString();
        //dd($mytime)
        $cadena=md5('2023'.$id);
        //dd ($cadena);
       //return view('cobros.ticketPDF', compact('cobros','totalreg','mytime','cadena'));

        $pdf = PDF::loadView(
                'consultaPermisos/ticketPDF', compact('permisos','totalreg','mytime','cadena','id'))->setPaper('letter', 'portrait',  array('UTF-8','UTF8'));
        $nombre='formato_'.$id.'.pdf';

        return $pdf->download($nombre);
    }
}
