<?php

namespace App\Http\Controllers;

use App\Tbl_Empleado_SIA;
use App\Puestos;
use App\Departamentos;
use App\Vacaciones;
use App\permiso_vacaciones;
use App\Parametros;
use App\EventualidadPeriodo;
use App\FormatoPermisos;



use Illuminate\Http\Request;

class TblEmpleadoSIAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $empleado){
       
        $tag = substr($empleado->noEmpleado,-9);
        $tag = str_pad($tag, 7, "0", STR_PAD_LEFT);
        
        $datosEmpleado = Tbl_Empleado_SIA::WHERE('Status_Emp','=','A')->WHERE('No_Empleado','=',$tag)
        ->orWHERE('Status_Emp','=','A')->WHERE('No_TAG','=',$tag)
        ->get();

        $getdep = Tbl_Empleado_SIA::WHERE('Status_Emp','=','A')->WHERE('No_Empleado','=',$tag)
        ->orWHERE('Status_Emp','=','A')->WHERE('No_TAG','=',$tag)
        ->get()->first();
        
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

        $vacaciones= Vacaciones::where('fk_no_empleado',$tag)->where('status','<>','CANCELADO')->get();

        $email = auth()->user()->email;

        $reportadirec = permiso_vacaciones::WHERE('id_puesto_solicitante','=',$cveci2)->get()->first();
        if (isset ($reportadirec))
            $reportadirec = $reportadirec->id_jefe;
        else
            $reportadirec = '';

     //   $datosJefe = Tbl_Empleado_SIA::WHERE('Status_Emp','=','A')->WHERE('Puesto','=',$reportadirec)->get();
        $datosJefe = Tbl_Empleado_SIA::join('permiso_vac','Tbl_Empleados_SIA.Puesto','=','permiso_vac.id_jefe')->where('Status_Emp','=','A')
        ->where('id_puesto_solicitante',$cveci2)->where('id_jefe','<>',$puesto)->orderby('Nom_Emp')->get();

        $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();
        
       // $eventualidad = EventualidadPeriodo::WHERE('no_empleado',$tag)->sum('eventualidad');
        //$periodo = EventualidadPeriodo::WHERE('no_empleado',$tag)->sum('periodo');

        if(auth()->user()->hasRole("Vicepresidente") ){
            return view('vacaciones.solicitud_vac2', compact('datosEmpleado','datosJefe','puestos','departamentos','vacaciones','parametros','eventualidad','periodo'));
        }else{
            return view('vacaciones.solicitud_vac', compact('datosEmpleado','datosJefe','puestos','departamentos','vacaciones','parametros','eventualidad','periodo'));
        }   
        //return $datosEmpleado;        
    }

    public function permisos(Request $empleado){
        
        $tag = substr($empleado->noEmpleado,-9);
        $tag = str_pad($tag, 7, "0", STR_PAD_LEFT);
        
        $datosEmpleado = Tbl_Empleado_SIA::WHERE('Status_Emp','=','A')->WHERE('No_Empleado','=',$tag)
        ->orWHERE('Status_Emp','=','A')->WHERE('No_TAG','=',$tag)
        ->get();

        $getdep = Tbl_Empleado_SIA::WHERE('Status_Emp','=','A')->WHERE('No_Empleado','=',$tag)
        ->orWHERE('Status_Emp','=','A')->WHERE('No_TAG','=',$tag)
        ->get()->first();
        
        if(isset($getdep)){
            $dep = $getdep->Departamento;
            $puesto = $getdep->Puesto;
            $cveci2 = $getdep->cveci2;
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
                
                $eventualidad = EventualidadPeriodo::WHERE('no_empleado',$tag)->where('fecha','<=',$antiguedad2)->where('fecha','>=',$antiguedad)->sum('eventualidad');
                $periodo = EventualidadPeriodo::WHERE('no_empleado',$tag)->where('fecha','<=',$antiguedad2)->where('fecha','>=',$antiguedad)->sum('periodo');
        
                
            }else{
                $antiguedad3 = $anio3.'-'.$mes_antiguedad.'-'.$dia_antiguedad;

                $eventualidad = EventualidadPeriodo::WHERE('no_empleado',$tag)->where('fecha','<=',$antiguedad3)->where('fecha','>=',$antiguedad)->sum('eventualidad');
                $periodo = EventualidadPeriodo::WHERE('no_empleado',$tag)->where('fecha','<=',$antiguedad3)->where('fecha','>=',$antiguedad)->sum('periodo');
        
            }
        }else{
            $dep = '';
            $puesto = '';
            $cveci2 = '';
            $antiguedad = '';
        }
        
       
        $puestos = Puestos::WHERE('Status','=','A')->get();
        $departamentos = Departamentos::WHERE('Status','=','A')->get();

        $vacaciones= Vacaciones::where('fk_no_empleado',$tag)->where('status','<>','CANCELADO')->get();

        $email = auth()->user()->email;

        $reportadirec = permiso_vacaciones::WHERE('id_puesto_solicitante','=',$cveci2)->get()->first();
        if (isset ($reportadirec))
            $reportadirec = $reportadirec->id_jefe;
        else
            $reportadirec = '';

      //  $permisos= FormatoPermisos::where('fk_no_empleado',$tag)->where('status','<>','CANCELADO')->get()->first();
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

     //   $datosJefe = Tbl_Empleado_SIA::WHERE('Status_Emp','=','A')->WHERE('Puesto','=',$reportadirec)->get();
        $datosJefe = Tbl_Empleado_SIA::join('permiso_vac','Tbl_Empleados_SIA.Puesto','=','permiso_vac.id_jefe')->where('Status_Emp','=','A')
        ->where('id_puesto_solicitante',$cveci2)->where('id_jefe','<>',$puesto)->orderby('Nom_Emp')->get();

        $parametros = Parametros::WHERE('Status','=','A')->where('modulo','1')->get();
        
       // $eventualidad = EventualidadPeriodo::WHERE('no_empleado',$tag)->sum('eventualidad');
       // $periodo = EventualidadPeriodo::WHERE('no_empleado',$tag)->sum('periodo');

     /*   if(auth()->user()->hasRole("Vicepresidente") ){
            return view('formatopermisos.index', compact('datosEmpleado','datosJefe','puestos','departamentos','vacaciones','parametros','eventualidad','periodo', 'no_permiso'));
        }else{*/
            return view('formatopermisos.index', compact('datosEmpleado','datosJefe','puestos','departamentos','vacaciones','parametros','eventualidad','periodo', 'no_permiso'));
      //  }   
        //return $datosEmpleado;        
    }


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

    /**
     * Display the specified resource.
     *
     * @param  \App\Tbl_Empleado_SIA  $tbl_Empleado_SIA
     * @return \Illuminate\Http\Response
     */
    public function show(Tbl_Empleado_SIA $tbl_Empleado_SIA)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tbl_Empleado_SIA  $tbl_Empleado_SIA
     * @return \Illuminate\Http\Response
     */
    public function edit(Tbl_Empleado_SIA $tbl_Empleado_SIA)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tbl_Empleado_SIA  $tbl_Empleado_SIA
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tbl_Empleado_SIA $tbl_Empleado_SIA)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tbl_Empleado_SIA  $tbl_Empleado_SIA
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tbl_Empleado_SIA $tbl_Empleado_SIA)
    {
        //
    }
}
