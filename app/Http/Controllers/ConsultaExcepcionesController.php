<?php

namespace App\Http\Controllers;

use App\Vacaciones;
use App\Puestos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Laracasts\Flash\Flash;
use JsValidator;
use PDF;

class ConsultaExcepcionesController extends Controller
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

         
            if($inicio != $fin){
                $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->select 
                ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','eventualidades')
                ->where('excepcion','1')->where('fech_ini_vac', '>=', $inicio)->where('fech_fin_vac', '<=', $fin)
                ->orderby('folio_vac','desc')
                ->get();
            }else{
                $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
                ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id_puesto')
                ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
                ->select 
                ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo','eventualidades')
                ->where('excepcion','1')
                ->where('fech_ini_vac', '=', $inicio)
                ->orderby('folio_vac','desc')
                ->get();
            }
           // dd($vacaciones->all());
            $puestos =Vacaciones::join('cat_puestos','vacaciones2.jefe_directo','=','cat_puestos.id_puesto')
            ->select 
            ('jefe_directo','cat_puestos.Puesto')
            ->where('cat_puestos.Nivel','1')
            ->groupby('jefe_directo')
             ->get();

        return view('consultaexcepciones.index', compact('vacaciones','puestos'));
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
       
        $vacaciones = Vacaciones::join('Tbl_Empleados_SIA','Tbl_Empleados_SIA.No_Empleado','=','Vacaciones2.fk_no_empleado')
        ->join('cat_puestos','Tbl_Empleados_SIA.puesto','=','cat_puestos.id')
        ->join('cat_departamentos','Tbl_Empleados_SIA.Departamento','=','cat_departamentos.id_Departamento')
        ->select 
        ('folio_vac','fecha_solicitud','fecha_aprobacion','fech_ini_vac','fech_fin_vac','Tbl_Empleados_SIA.Id_Planta','Tbl_Empleados_SIA.Id_Turno','No_Empleado','Nom_Emp','Ap_Pat','Ap_Mat','cat_puestos.Puesto','cat_departamentos.Departamento','Vacaciones2.status','fk_no_empleado','jefe_directo','Modulo')
        ->where('folio_vac',$id)
        ->orderby('folio_vac','desc')
        ->get();

        $totalreg =Vacaciones::where('folio_vac',$id)
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
                'consultaexcepciones/ticketPDF', compact('vacaciones','totalreg','mytime','cadena','id'))->setPaper('letter', 'portrait',  array('UTF-8','UTF8'));
        $nombre='formato_'.$id.'.pdf';        

        return $pdf->download($nombre);
    }
}
