<?php

namespace App\Http\Controllers;

use App\Vacaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Laracasts\Flash\Flash;
use JsValidator;
use PDF;

class EstatusVacacionesController extends Controller
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

        if(auth()->user()->hasRole("Team Leader")){

            if($inicio != $fin){
                $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->select
                ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','Modulo','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto'/*,'cat_departamentos.Departamento'*/,'Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','autorizado_por','vacaciones2.updated_at')
                ->where('fech_ini_vac', '>=', $inicio)->where('fech_fin_vac', '<=', $fin)
                ->orderby('folio_vac','desc')
                ->get();
            }else{

                $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->select
                ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','autorizado_por','vacaciones2.updated_at')
                ->where('fech_ini_vac', '=', $inicio)
                ->orderby('folio_vac','desc')
                ->get();
            }
        }else{
            if($inicio != $fin){
                $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->select
                ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','autorizado_por','vacaciones2.updated_at')
                ->where('fech_ini_vac', '>=', $inicio)->where('fech_fin_vac', '<=', $fin)
                ->orderby('folio_vac','desc')
                ->get();
            }else{
                $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->select
                ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','autorizado_por','vacaciones2.updated_at')
                ->where('fech_ini_vac', '=', $inicio)
                ->orderby('folio_vac','desc')
                ->get();
            }
        }
        return view('estatusvacaciones.index', compact('vacaciones'));
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
}
