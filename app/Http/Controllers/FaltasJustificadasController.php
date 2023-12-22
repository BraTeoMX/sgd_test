<?php

namespace App\Http\Controllers;

use App\documentopermisos;
use App\Faltas;
use App\FaltasJustificadas;
use App\MotivosFaltasJustificadas;
use App\Tbl_Empleado_SIA;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FaltasJustificadasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        #$motivos =
        return view('faltasjustificadas.index');
    }

    public function create(Request $request)
    {
        $no_emp = str_pad($request->no_empleado, 7, "0", STR_PAD_LEFT);
        $empleado = Tbl_Empleado_SIA::join('cat_departamentos', 'cat_departamentos.id_Departamento', 'tbl_empleados_sia.Departamento')
            ->Where('No_Empleado', $no_emp)
            ->Where('Status_Emp', 'A')
            ->get();
        $motivos = MotivosFaltasJustificadas::pluck('motivo', 'id');

        return view('faltasjustificadas.index', compact('empleado', 'motivos'));
    }

    public function store(Request $request)
    {
        $no_aux = str_pad($request->no_aux, 7, "0", STR_PAD_LEFT);
        $falta = new FaltasJustificadas();
        $falta->folio_falta = $this->getfolio($request->no_emp);
        $falta->fecha_reg = date('Y-m-d');
        $falta->fk_no_empleado = $request->no_emp;
        $falta->fecha_inicio_justificar = $request->fecha_falta;
        $falta->fecha_fin_justificar = $request->fecha_falta;
        $falta->motivo_falta = $request->motivo;
        $falta->comentario = $request->comentario;
        $falta->estatus_falta = 'APLICADO';
        if ($request->file('file') != NULL) {
            $falta = $this->guardarDocumento($request, $falta);
        }
        $falta->fk_no_jefe = $request->no_aux;
        $falta->puesto_jefe = $this->getpuestojefe($no_aux);
        $falta->save();
        flash('SE HA JUSTIFICADO LA FALTA.')->success()->important();
        return redirect('/faltasjustificadas');
    }

    public function guardarDocumento($request, $falta)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf'
        ]);
        $file = $request->file('file');
        #$path = $file->store('Documentos');
        #$file->move(public_path('Documentos'));
        $path = $file->move(public_path('Documentos'), $file->store('public/Documentos'));
        #echo $path;
        #$file->cut(public_path().'/Documentos',$path);
        #$path = $file->move(public_path('Documentos'), $file->getClientOriginalName());

        $falta->nombre_archivo = $request->file->getClientOriginalName(); #<-#
        $falta->ruta_archivo = $path;
        #echo $falta->ruta_archivo;
        return $falta;
    }

    public function nuevoarchivo(Request $request)
    {
        #echo $request->folio;
        $falta = FaltasJustificadas::where('folio_falta', $request->folio)->get()->first();
        #$falta = new FaltasJustificadas();
        $falta = $this->guardarDocumento($request, $falta);
        $falta->save();
        flash('SE HA ACTUALIZADO EL DOCUMENTO.')->success()->important();
        return redirect('/faltasjustificadas');
    }

    public function cancelarfaltaj(Request $request)
    {
        #echo 'hola';
        $falta = FaltasJustificadas::where('folio_falta', $request->folio)->get()->first();
        #$falta = new FaltasJustificadas();
        $falta->estatus_falta = ('CANCELADO');
        $falta->save();
        flash('SE HA CANCELADO LA FALTA CON EL FOLIO: ' . $request->folio)->error()->important();
        return redirect('/faltasjustificadas');
    }

    public function updatearchivo(Request $request)
    {
        $faltajustificada = FaltasJustificadas::join('tbl_empleados_sia', 'tbl_empleados_sia.No_Empleado', 'faltas_justificadas.fk_no_empleado')
            ->where('folio_falta', $request->folio)
            ->groupBy('faltas_justificadas.fk_no_empleado')
            ->get();

        #echo $faltajustificada;
        return view('faltasjustificadas.actualizararchivo', compact('faltajustificada'));
    }

    public function getpuestojefe($id)
    {
        $puestoJefe = Tbl_Empleado_SIA::join('cat_puestos', 'cat_puestos.id_puesto', 'tbl_empleados_sia.Puesto')
            ->select('cat_puestos.Puesto')
            ->where('No_Empleado', $id)
            ->get()->first();
        return $puestoJefe->Puesto;
    }

    public function getfolio($no_empleado)
    {
        $anio = date('y');
        $folio = FaltasJustificadas::select('folio_falta')->where('folio_falta', '!=', 'NULL')->orderBy('id', 'desc')->get()->first();
        $planta = Tbl_Empleado_SIA::select('Id_Planta')->where('No_Empleado', $no_empleado)->get()->first();
        if ($folio == null) {
            $consecutivo = 1;
        } else {
            $consecutivo = intval(substr($folio->folio_falta, 4, 4));
            $consecutivo = $consecutivo + 1;
        }
        if ($planta->Id_Planta == 'Intimark1') {
            $planta = 'A';
        } else {
            $planta = 'B';
        }
        $consecutivo = str_pad($consecutivo, 4, '0', STR_PAD_LEFT);
        $folio = $anio . 'FJ' . $consecutivo . $planta;
        return $folio;
    }

    public function reporte()
    {
        #echo 'hola desde el reporte';
        return view('faltasjustificadas.reporte');
    }

    public function busquedareporte(Request $request)
    {
        $no_emp = str_pad($request->no_empleado, 7, "0", STR_PAD_LEFT);

        if($request->no_empleado != null){
            $reportes = FaltasJustificadas::join('cat_motivos_faltasjusti', 'cat_motivos_faltasjusti.id', 'faltas_justificadas.motivo_falta')
            ->join('tbl_empleados_sia', 'tbl_empleados_sia.no_empleado', 'faltas_justificadas.fk_no_empleado')
            ->join('cat_departamentos', 'cat_departamentos.id_Departamento', 'tbl_empleados_sia.Departamento')
            ->WHERE('fk_no_empleado', $no_emp)
            ->orderBy('faltas_justificadas.fk_no_empleado')
            ->get();
        }else{
            $inicio= ($request->filled('inicio_fecha')) ? $request->inicio_fecha : date('Y-m-d');
            $fin= ($request->filled('fin_fecha')) ? $request->fin_fecha : date('Y-m-d');

           // dd($request->all());
            if($inicio != $fin){
                    $reportes = FaltasJustificadas::join('cat_motivos_faltasjusti', 'cat_motivos_faltasjusti.id', 'faltas_justificadas.motivo_falta')
                    ->join('tbl_empleados_sia', 'tbl_empleados_sia.no_empleado', 'faltas_justificadas.fk_no_empleado')
                    ->join('cat_departamentos', 'cat_departamentos.id_Departamento', 'tbl_empleados_sia.Departamento')
                    ->where('fecha_inicio_justificar', '>=', $inicio)->where('fecha_fin_justificar', '<=', $fin)
                    ->groupby('folio_falta')
                    ->get();
            }else{
                    $reportes = FaltasJustificadas::join('cat_motivos_faltasjusti', 'cat_motivos_faltasjusti.id', 'faltas_justificadas.motivo_falta')
                    ->join('tbl_empleados_sia', 'tbl_empleados_sia.no_empleado', 'faltas_justificadas.fk_no_empleado')
                    ->join('cat_departamentos', 'cat_departamentos.id_Departamento', 'tbl_empleados_sia.Departamento')
                    ->Where('fecha_inicio_justificar', $inicio)
                    ->groupby('folio_falta')
                    ->get();
            }

        }

        return view('faltasjustificadas.reporte', compact('reportes'));
    }

    public function verarchivofj($folio)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ajaxfechainicio(Request $request)
    {
        $falta = Faltas::where('no_empleado', $request->no_emp)
            ->where('fecha_falta', $request->fecha_ini)
            ->where('idIncidencia', 'INASISTENC')
            ->get()->count();
        return $falta;
    }

    public function ajaxfechafin(Request $request)
    {
        $falta = Faltas::where('no_empleado', $request->no_emp)
            ->where('fecha_falta', $request->fecha_fin)
            ->where('idIncidencia', 'INASISTENC')
            ->get()->count();
        return $falta;
    }
}
