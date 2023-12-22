<?php

namespace App\Http\Controllers;

use App\Incapacidades;
use App\Mail\IncapacidadNotificacion;
use App\permiso_vacaciones;
use App\Tbl_Empleado_SIA;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class IncapacidadesController extends Controller
{
    public function index()
    {
        return view('incapacidades.index');
    }

    public function create(Request $request)
    {
        $no_emp = str_pad($request->no_empleado, 7, "0", STR_PAD_LEFT);
        $empleado = Tbl_Empleado_SIA::join('cat_departamentos', 'cat_departamentos.id_Departamento', 'tbl_empleados_sia.Departamento')
            ->Where('No_Empleado', $no_emp)
            ->Where('Status_Emp', 'A')
            ->get();
        #$motivos = MotivosFaltasJustificadas::pluck('motivo', 'id');
        return view('incapacidades.index', compact('empleado'));
    }

    public function store(Request $request)
    {
        $incapacidades = new Incapacidades;
        $incapacidades->folio_incapacidad = $request->folio;
        $incapacidades->fecha_registro = date('Y-m-d');
        $incapacidades->fk_empleado = $request->no_emp;
        $incapacidades->fecha_inicio = $request->inicio;
        $incapacidades->fecha_fin = $request->fin;
        $incapacidades->dias = $request->dias;
        $incapacidades->tipo_incapacidad = $request->tipo;
        $incapacidades->ramo_seguro = $request->ramo;
        $incapacidades->riesgo = $request->riesgo;
        $incapacidades->status = 'APLICADO';
        #$incapacidades->comentario = $request->comentario;
        $incapacidades->comentario = Crypt::encrypt($request->comentario);
        #echo $request->fileincapacidad;
        #echo $request->filest;
        $incapacidades = $this->guardarforIncapacidad($request, $incapacidades);
        if ($request->file('filest') != NULL) {
            $incapacidades = $this->guardarst($request, $incapacidades);
        }
        /*$per_ = Tbl_Empleado_SIA::join('cat_puestos', 'Tbl_Empleados_SIA.puesto', '=', 'cat_puestos.id_puesto')
            ->where('No_Empleado', $reg_sol_per->autorizar)->where('Status_Emp', 'A')
            ->select('Nivel')->get()->first();*/
        if ($incapacidades->save()) {
            $this->enviarcorreo($request);
            flash('SE HA REGISTRADO LA INCAPACIDAD')->success()->important();
            return redirect('/incapacidades');
        } else {
            flash('Ocurrio un error al intentar registrar la incapacidad')->error()->important();
            return redirect('/incapacidades');
        }
    }

    public function enviarcorreo($request)
    {
        $cveci2 = Tbl_Empleado_SIA::where('no_empleado', $request->no_emp)->where('status_emp', 'A')->get()->first();
        $jefe = Tbl_Empleado_SIA::select('No_Empleado') ///QUERY PARA BUSCAR AL JEFE DIRECTO
            ->join('cat_puestos', 'cat_puestos.id_puesto', 'tbl_empleados_sia.Puesto')
            ->join('permiso_vac', 'permiso_vac.id_jefe', 'tbl_empleados_sia.Puesto')
            ->where('tbl_empleados_sia.status_emp', 'A')
            ->where('permiso_vac.id_puesto_solicitante', $cveci2->cveci2)
            ->where('tbl_empleados_sia.Id_Planta', $cveci2->Id_Planta)
            ->where('cat_puestos.Nivel', '<=', '2')
            ->orderBy('cat_puestos.Nivel', 'Desc')
            ->get()->first();
        $numjefe = ltrim($jefe->No_Empleado, '0');
        $correo = User::select('email')->where('no_empleado', $numjefe)->where('inactivo', '0')->get()->first();
        #echo $correo->email;
        #dd($cveci2);
        $emp = Incapacidades::where('folio_incapacidad', $request->folio)->orderBy('id', 'Desc')->get();
        #echo $emp;
        Mail::to($correo->email)
            #Mail::to('aperez@intimark.com.mx')
            ->send(new IncapacidadNotificacion($emp));
    }

    public function guardarst($request, $incapacidades)
    {
        /*echo 'guardar st';
        echo $request->filest;
        */
        $request->validate([
            'filest' => 'file|mimes:pdf'
        ]);
        $file = $request->file('filest');
        $path = $file->move(public_path('Documentos'), $file->store('public/Documentos'));
        if ($request->doccalificacion === 'ST-9') {
            $incapacidades->formato_st9 = $request->filest->getClientOriginalName(); #<-#
            $incapacidades->ruta_st9 = $path;
        } elseif ($request->doccalificacion === 'ST-7') {
            $incapacidades->formato_st7 = $request->filest->getClientOriginalName(); #<-#
            $incapacidades->ruta_st7 = $path;
        } elseif ($request->doccalificacion === 'ST-3') {
            $incapacidades->formato_st3 = $request->filest->getClientOriginalName(); #<-#
            $incapacidades->ruta_st3 = $path;
        }
        return $incapacidades;
    }

    public function guardarforIncapacidad($request, $incapacidades)
    {
        $request->validate([
            'fileincapacidad' => 'file|mimes:pdf'
        ]);
        $file = $request->file('fileincapacidad');
        $path = $file->move(public_path('Documentos'), $file->store('public/Documentos'));
        $incapacidades->formato_incapacidad = $request->fileincapacidad->getClientOriginalName();
        $incapacidades->ruta_incapacidad = $path;
        return $incapacidades;
    }
    public function guardarst9($request, $incapacidades)
    {
        $request->validate([
            'filest9' => 'file|mimes:pdf'
        ]);
        $file = $request->file('filest9');
        $path = $file->move(public_path('Documentos'), $file->store('public/Documentos'));
        $incapacidades->formato_st9 = $request->filest9->getClientOriginalName(); #<-#
        $incapacidades->ruta_st9 = $path;
        return $incapacidades;
    }

    public function guardarst7($request, $incapacidades)
    {
        $request->validate([
            'filest7' => 'file|mimes:pdf'
        ]);
        $file = $request->file('filest7');
        $path = $file->move(public_path('Documentos'), $file->store('public/Documentos'));
        $incapacidades->formato_st7 = $request->filest7->getClientOriginalName(); #<-#
        $incapacidades->ruta_st7 = $path;
        return $incapacidades;
    }

    public function guardarst3($request, $incapacidades)
    {
        $request->validate([
            'filest3' => 'file|mimes:pdf'
        ]);
        $file = $request->file('filest3');
        $path = $file->move(public_path('Documentos'), $file->store('public/Documentos'));
        $incapacidades->formato_st3 = $request->filest3->getClientOriginalName(); #<-#
        $incapacidades->ruta_st3 = $path;
        return $incapacidades;
    }

    public function guardaralta($request, $incapacidades)
    {
        $request->validate([
            'filealta' => 'file|mimes:pdf'
        ]);
        $file = $request->file('filealta');
        $path = $file->move(public_path('Documentos'), $file->store('public/Documentos'));
        $incapacidades->formato_alta = $request->filealta->getClientOriginalName();
        $incapacidades->ruta_alta = $path;
        return $incapacidades;
    }

    public function reporte()
    {
        return view('incapacidades.reporte');
    }

    public function busquedaincapacidades(Request $request)
    {
        if (($request->inicio_fecha != '' && $request->fin_fecha == '') || ($request->inicio_fecha == '' && $request->fin_fecha != '')) {
            flash('Buscar por Numero de empleado O un rango de fechas')->warning()->important();
            return view('incapacidades.reporte');
        }

        $no_emp = str_pad($request->no_empleado, 7, "0", STR_PAD_LEFT);
        if ($request->inicio_fecha==''){
            $fecha_inicio = "1999-01-01";
            $fecha_fin = date("Y-m-d");
            $reportes = Incapacidades::join('tbl_empleados_sia', 'tbl_empleados_sia.no_empleado', 'incapacidades.fk_empleado')
            ->join('cat_departamentos', 'cat_departamentos.id_Departamento', 'tbl_empleados_sia.Departamento')
            ->WHERE('fk_empleado', $no_emp)
            ->WHERE('status_emp', 'A')
            ->Where('fecha_inicio', '>=',$fecha_inicio)
            ->Where('fecha_inicio', '<', $fecha_fin)
           #->groupby('folio_incapacidad')
           ->get();
        }else{
            $fecha_inicio = $request->inicio_fecha;
            $fecha_fin = $request->fin_fecha;
            $reportes = Incapacidades::join('tbl_empleados_sia', 'tbl_empleados_sia.no_empleado', 'incapacidades.fk_empleado')
            ->join('cat_departamentos', 'cat_departamentos.id_Departamento', 'tbl_empleados_sia.Departamento')
            ->WHERE('status_emp', 'A')
            ->Where('fecha_inicio', '>=',$fecha_inicio)
            ->Where('fecha_inicio', '<', $fecha_fin)
           #->groupby('folio_incapacidad')
           ->get();
        }

    /*    $reportes = Incapacidades::join('tbl_empleados_sia', 'tbl_empleados_sia.no_empleado', 'incapacidades.fk_empleado')
            ->join('cat_departamentos', 'cat_departamentos.id_Departamento', 'tbl_empleados_sia.Departamento')
            ->WHERE('fk_empleado', $no_emp)
            ->WHERE('status_emp', 'A')
            ->orWhere('fecha_inicio', '>=',$fecha_inicio)
            ->Where('fecha_inicio', '<', $fecha_fin)
           ->groupby('folio_incapacidad')
           ->get();*/
        /*echo $reportes;
        echo $reportes->comentario;
        try {
            $decrypted = Crypt::decrypt($reportes->comentario);
            echo Crypt::decrypt($reportes->comentario);
        } catch (Exception $e) {
        }
        echo $decrypted;
        */
        return view('incapacidades.reporte', compact('reportes'));
    }

    public function cancelarincapacidad($id)
    {
        echo $id;
        #$incapacidades = Incapacidades::where('id', $request->id)->get()->first();
        $incapacidad = Incapacidades::where('id', $id)->get()->first();
        echo $incapacidad;
        $incapacidad->status = 'CANCELADO';
        $incapacidad->save();
        flash('SE HA CANCELADO LA INCAPACIDAD CON EL FOLIO: ' . $incapacidad->folio_incapacidad)->error()->important();
        return redirect('/incapacidades');
    }

    public function editarincapacidad(Request $request)
    {
        $incapacidades = Incapacidades::where('id', $request->id)->get()->first();
        #echo $request->oficioentregado;
        if (
            $request->fileincapacidad === null && $request->filest9 === null && $request->filest7 === null &&
            $request->filest3 === null && $request->filealta === null && $request->oficioentregado === '0'
        ) {
            #echo 'oficio es 0';
            flash('NO SE REALIZARON ACTUALIZACIONES')->success()->important();
            return redirect('/incapacidades.reporte');
        } else {
            if ($request->oficioentregado !== '0') {
                $incapacidades = $this->oficioentregado($request, $incapacidades);
            }
            if ($request->fileincapacidad !== null) {
                if (file_exists($incapacidades->ruta_incapacidad)) {
                    unlink($incapacidades->ruta_incapacidad);
                }
                $incapacidades = $this->guardarforIncapacidad($request, $incapacidades);
            }
            if ($request->filest9 !== null) {
                if (file_exists($incapacidades->ruta_st9)) {
                    unlink($incapacidades->ruta_st9);
                }
                $incapacidades = $this->guardarst9($request, $incapacidades);
            }
            if ($request->filest7 !== null) {
                if (file_exists($incapacidades->ruta_st7)) {
                    unlink($incapacidades->ruta_st7);
                }
                $incapacidades = $this->guardarst7($request, $incapacidades);
            }
            if ($request->filest3 !== null) {
                if (file_exists($incapacidades->ruta_st3)) {
                    unlink($incapacidades->ruta_st3);
                }
                $incapacidades = $this->guardarst3($request, $incapacidades);
            }
            if ($request->filealta !== null) {
                if (file_exists($incapacidades->ruta_alta)) {
                    unlink($incapacidades->ruta_alta);
                }
                $incapacidades = $this->guardaralta($request, $incapacidades);
            }
            $incapacidades->save();
            flash('SE HA ACTUALIZADO.')->success()->important();
            return redirect('/incapacidades.reporte');
        }
    }

    public function oficioentregado($request, $incapacidades)
    {
        #echo $request->oficioentregado;
        #echo $request->oficioentregado;
        $incapacidades->oficioentregado = $request->oficioentregado;
        return $incapacidades;
    }

    public function show($id)
    {
        //
        $incapacidad = Incapacidades::join('tbl_empleados_sia', 'tbl_empleados_sia.no_empleado', 'incapacidades.fk_empleado')
            ->join('cat_departamentos', 'cat_departamentos.id_Departamento', 'tbl_empleados_sia.Departamento')
            ->where('id', $id)
            ->where('tbl_empleados_sia.status_emp','A')
            ->get();
        return view('incapacidades.actualizar', compact('incapacidad'));
    }

    public function edit($id)
    {
        //
        echo 'hola edit';
    }

    public function update(Request $request, $id)
    {
        //
        echo 'hola update';
    }

    public function destroy($id)
    {
        //
    }

    public function revisarfolios()
    {
        $res = 'Hola';
        return $res;
    }
}
