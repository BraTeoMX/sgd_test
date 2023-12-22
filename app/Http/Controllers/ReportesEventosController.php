<?php

namespace App\Http\Controllers;

use App\Departamentos;
use Illuminate\Http\Request;
use App\RegistrarAsistencias;
use App\RegistrarEventos;
use App\Tbl_Empleado_SIA;
use App\Puestos;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteEventosExport;


class ReportesEventosController extends Controller
{
    public function obtenerMeses(Request $request, $evento)
    {
        // Aquí debes recuperar los meses disponibles para el evento
        $mesesDisponibles = RegistrarAsistencias::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as fecha")
            ->where('id_evento', $evento)
            ->distinct()
            ->get()
            ->pluck('fecha');

        return response()->json($mesesDisponibles);
         // Obtiene todos los eventos registrados
    $eventos = RegistrarEventos::all();

    // Obtiene el tipo de evento seleccionado desde la solicitud
    $optionsave = $request->tipo_evento;

    $nombre = RegistrarAsistencias::where('id_evento', $optionsave)
        ->value('tipo_evento');

    // Inicializa un arreglo para los valores de selección de fechas
    $fechasSeleccion = [];

    foreach ($mesesDisponibles as $mesAnio) {
        $fecha = \Carbon\Carbon::parse($mesAnio->fecha);
        $fechasSeleccion[$mesAnio->fecha] = $fecha->format('F Y');
      //  dd($fechasSeleccion);
    }

    // Obtiene la fecha seleccionada desde la solicitud
    $fecha = $request->selecmes;

    // Inicializa la consulta de reportes sin filtros de fecha
    $reportesQuery = RegistrarAsistencias::select('id', 'id_evento', 'no_empleados', 'No_Tag', 'tipo_evento', 'nombre_empleado', 'Puesto', 'created_at', 'asistencia', 'Planta', 'Departamento')
        ->where('id_evento', $evento);

    // Si se seleccionó una fecha, filtra por ese rango
    if ($fecha) {
        $reportesQuery->whereDate('created_at', $fecha);
    }

    // Obtiene los reportes de asistencia
    $reportes = $reportesQuery->get();

    $totalRegistros = $reportes->count();

    // Devuelve la vista 'eventos.ReportesEventos' con los reportes y las fechas únicas
    return view('eventos.ReportesEventos', compact('eventos', 'reportes', 'totalRegistros', 'optionsave', 'nombre', 'fechasSeleccion'));
    }
    public function ReportesEventos(Request $request)
    {
        // Obtiene todos los eventos registrados
        $eventos = RegistrarEventos::all();
        $evento = $request->input('evento');
        // Obtiene el tipo de evento seleccionado desde la solicitud
        $optionsave = $request->tipo_evento;
        $mes = $request->input('mes');

        $nombre = RegistrarAsistencias::where('id_evento', $optionsave)
            ->value('tipo_evento');

        // Obtiene los meses y años únicos de los registros
        $mesesAnios = RegistrarAsistencias::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as fecha")
            ->where('id_evento', $optionsave)
            ->distinct()
            ->get();
            $mesesSeleccion = RegistrarAsistencias::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as fecha")
            ->where('id_evento', $evento)
            ->distinct()
            ->get();

        // Inicializa un arreglo para los valores de selección de fechas
        $fechasSeleccion = [];

        foreach ($mesesAnios as $mesAnio) {
            $fecha = \Carbon\Carbon::parse($mesAnio->fecha);
            $fechasSeleccion[$mesAnio->fecha] = $fecha->format('F Y');
            //dd($fechasSeleccion);
        }

        // Obtiene la fecha seleccionada desde la solicitud
        $fecha = $request->created_at;

        // Inicializa la consulta de reportes sin filtros de fecha
        $reportesQuery = RegistrarAsistencias::select('id', 'id_evento', 'no_empleados', 'No_Tag', 'tipo_evento', 'nombre_empleado', 'Puesto', 'created_at', 'asistencia', 'Planta', 'Departamento')
            ->where('id_evento', $optionsave);

        // Si se seleccionó una fecha, filtra por ese rango
        if ($fecha) {
            $reportesQuery->whereDate('created_at', $fecha);
        }

        // Obtiene los reportes de asistencia
        $reportes = $reportesQuery->get();

        $totalRegistros = $reportes->count();

        // Devuelve la vista 'eventos.ReportesEventos' con los reportes y las fechas únicas
        return view('eventos.ReportesEventos', compact('eventos', 'reportes', 'totalRegistros', 'optionsave', 'nombre', 'fechasSeleccion', 'request','fecha','mesesSeleccion','mes'));


    }
    // Método para generar un reporte de eventos
    public function GenerarReporte(Request $request)
    {
        // Obtiene el evento y el mes seleccionado desde la solicitud
        $evento = $request->input('tipo_evento');
        $mes = $request->input('created_at');

        // Obtiene todos los eventos registrados
        $eventos = RegistrarEventos::all();

        // Obtiene el tipo de evento seleccionado desde la solicitud
        $optionsave = $request->tipo_evento;

        $nombre = RegistrarAsistencias::where('id_evento', $optionsave)
            ->value('tipo_evento');

        // Obtiene los meses y años únicos de los registros
        $mesesSeleccion = RegistrarAsistencias::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as fecha")
            ->where('id_evento', $evento)
            ->distinct()
            ->get();

        // Inicializa la consulta de reportes sin filtros de fecha
        $reportesQuery = RegistrarAsistencias::select('id', 'id_evento', 'no_empleados', 'No_Tag', 'tipo_evento', 'nombre_empleado', 'Puesto', 'created_at', 'asistencia', 'Planta', 'Departamento')
            ->where('id_evento', $evento)
            ->whereYear('created_at', '=', substr($mes, 0, 4))
            ->whereMonth('created_at', '=', substr($mes, 5, 2))
            ->get();

        // Cambiar el encabezado de la columna "Asistencia" a "Rollos entregados" si la opción seleccionada es "5"
        $encabezadoColumna = ($optionsave == '5') ? 'Rollos' : 'Asistencia';

        // Obtiene el número total de registros
        $totalRegistros = $reportesQuery->count();

        // Devuelve la vista 'eventos.ReportesEventos' con los reportes y las fechas únicas
        // Aquí debes crear o inicializar tu objeto PDF
        // Resto de tu código para configurar el objeto PDF
        $datosReporte = [
            'eventos' => $eventos,
            'reportesQuery' => $reportesQuery,
            'totalRegistros' => $totalRegistros,
            'optionsave' => $optionsave,
            'nombre' => $nombre,
            'request' => $request,
            'mes' => $mes,
            'mesesSeleccion' => $mesesSeleccion,
            'encabezadoColumna' => $encabezadoColumna,

        ];

        // Renderiza la vista directamente
        $pdfView = view('eventos.PDFReportView', compact('datosReporte'))->render();

        // Configura el objeto PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($pdfView);
        $dompdf->setPaper('A4', 'landscape');

        // Renderiza el PDF
        $nombreArchivo = 'Reporte' . ' ' . $nombre;

        $dompdf->render($nombreArchivo);

        if ($request->has('excel')) {
         // dd($request->all());  // Redirige a la ruta de exportarExcel
            return redirect()->route('eventos.exportarExcel', $request->all());
        }

        return $dompdf->stream($nombreArchivo);
    }

    public function exportarExcel(Request $request)
{
    $optionsave = $request->tipo_evento;
    $fechaSeleccionada = $request->created_at;
    $mes = $request->input('created_at');
    // Lógica para obtener los datos y configurar la exportación
    $reportesQuery = RegistrarAsistencias::select('id', 'id_evento', 'no_empleados', 'No_Tag', 'tipo_evento', 'nombre_empleado', 'Puesto', 'created_at', 'asistencia', 'Planta', 'Departamento')
    ->where('id_evento', $optionsave)
    ->whereYear('created_at', '=', substr($mes, 0, 4))
    ->whereMonth('created_at', '=', substr($mes, 5, 2))
    ->get();
    //dd($reportesQuery);
    // Si se seleccionó una fecha, filtra por ese rango

    $reportes = $reportesQuery;

    // Otras configuraciones según tus necesidades
    $nombre = RegistrarAsistencias::where('id_evento', $optionsave)->value('tipo_evento');

    $mesesSeleccion = RegistrarAsistencias::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as fecha")
        ->where('id_evento', $optionsave)
        ->distinct()
        ->get();

    $totalRegistros = $reportes->count();

    $datosReporte = [
        'reportesQuery' => $reportesQuery, // Puedes seguir utilizando $reportesQuery aquí
        'totalRegistros' => $totalRegistros,
        'optionsave' => $optionsave,
        'nombre' => $nombre,
        'request' => $request,
        'mesesSeleccion' => $mesesSeleccion,
        'tipoEvento' => $nombre,
    ];

    // Luego, puedes exportar el archivo de Excel usando la clase 'ReporteEventosExport'
    return Excel::download(new ReporteEventosExport($datosReporte), 'Reporte '.$nombre.'.xlsx');
}

    // Método para registrar asistencias a eventos
    public function registrarBecario(Request $request) {
        $optionsave = $request->tipo_evento;
        $becario = $request->input('nombre_becario');
        $Tipss_eventos = RegistrarEventos::where('cve_evento', $optionsave)
        ->select('tipo_evento','id_evento','id')
        ->first();

        $request->validate([
            'nombre_becario' => 'required|string|max:255',
            'planta' => 'required|string', // Puedes agregar reglas de validación según tus necesidades
        ]);

          //dd($id_reg);
        // Crea una nueva instancia del modelo RegistrarAsistencias y guarda los datos
        $evento = new RegistrarAsistencias();
        $evento->no_empleados = "Becario";
        $evento->No_Tag = "Becario";
        $evento->Departamento = "Becario";
        $evento->Puesto = "Becario";
        $evento->nombre_empleado = $request->input('nombre_becario');
        $evento->id_evento = $Tipss_eventos->id_evento;
        $evento->tipo_evento = $Tipss_eventos->tipo_evento;
        $evento->Planta = $request->input('planta'); // Guarda la planta seleccionada

        // Guarda el registro en la base de datos
        $evento->save();
        $id_reg = RegistrarAsistencias::where ('nombre_empleado', $becario)
        ->where ( 'tipo_evento', $Tipss_eventos->tipo_evento)
        ->value ('id');
        // Devuelve una respuesta de éxito (puedes personalizarlo según tus necesidades)
        return response()->json($id_reg);
    }

    public function AsistenciaBecarios(Request $request){
    $idregistro = $request->numeroRegistro;


    $optionsave = $request->tipo_evento;
    $Tipss_eventos = RegistrarEventos::where('cve_evento', $optionsave)
    ->value('tipo_evento');
    //return response()->json($idregistro);
    // Busca el registro por el valor de "id"
    $TablaEventos = RegistrarAsistencias::select('id', 'id_evento', 'no_empleados', 'No_Tag', 'tipo_evento', 'nombre_empleado', 'Puesto', 'Planta', 'Departamento', 'asistencia')
    ->where('tipo_evento', $Tipss_eventos)
    ->where('id', $idregistro)
    ->first();
    $nombre = $TablaEventos->nombre_empleado;

    if ($TablaEventos && ($TablaEventos->asistencia == null)) {

        $TablaEventos->update(['asistencia' => 'asistencia confirmada']);
        //apartado para realizar un conteo de acuerdo a la consulta
/*
        $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
        $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'asistencia confirmada')
        ->where('Planta', 'Intimark1')
        ->count();
        // dd($ConteoRegistroIxtlahuaca);
        $RegistroIxtlahuacaTotal = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('Planta', 'Intimark1')
        ->count();
        // dd($RegistroIxtlahuacaTotal);
        $ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'asistencia confirmada')
        ->where('Planta', 'Intimark2')
        ->count();
        $RegistroSanBartoloTotal = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('Planta', 'Intimark2')
        ->count();
        $ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'asistencia confirmada')
        ->count();
        $ConteoRegistrosTotal = RegistrarAsistencias::where('tipo_evento', $contador)
        ->count();
        $ConteoRegistrosTotal = $RegistroIxtlahuacaTotal + $RegistroSanBartoloTotal;
        //Fin de apartado de Contador
        return response()->json($TablaEventos);

        //return back()->with('error', 'No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
        return view('eventos.RegistroAsistencias', compact(
            'eventos',
            'TablaEventos',
            'optionsave',
            'ConteoRegistroIxtlahuaca',
            'ConteoRegistroSanBartolo',
            'ConteoRegistros',
            'ConteoRegistrosTotal',
            'RegistroIxtlahuacaTotal',
            'RegistroSanBartoloTotal',
            'contador'
        ));*/
        return response()->json($TablaEventos);
    }else {
        if(!$TablaEventos){
            return response()->json(['error' => 'No se encontró un registro para este evento']);
        }elseif ($TablaEventos->asistencia == 'asistencia confirmada') {
            return response()->json(['error' => 'Ya se registró la asistencia de esta persona']);
        }
    }

}
public function RegistrarAsistencias(Request $request)
{
    $eventos = RegistrarEventos::where('conf_pre_regis', 'No Requiere Registro')->get();
    //codigo extraido de tabla cat_eventos en la columna cve_evento
    $optionsave = $request->tipo_evento;
    //dd($request->tipo_evento);
    //dato_evento trae el numero de tag o numero de empleado
    $datos_evento = $request->datos_evento;
    //dd($datos_evento);
    // Obten el evento seleccionado
    $evento = RegistrarEventos::where('cve_evento', $optionsave)->first();
   // dd($optionsave);
   $Asistencia = Tbl_Empleado_SIA::select('No_Empleado', 'Id_Planta', 'No_TAG', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat','Puesto', 'Departamento','Fecha_In')
    ->where(function ($query) use ($datos_evento) {
        $query->where('No_Empleado', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
            ->orWhere('No_TAG', $datos_evento);
    })
    ->where('Status_Emp', 'A')
    ->first();
    if (!$evento) {
        return back()->with('error', 'Evento no válido');
    }
    //dd($request->all());
    //$Asistencia = RegistrarAsistencias::all();
    session()->put('datos_evento', $request->tipo_evento);
    $datos_evento = $request->datos_evento;
    if(strlen($datos_evento)==10){
        $datos_evento = substr($datos_evento,-9);
    }
/////////////////////////////////
if($optionsave=='EntPH'){
$Asistencia = Tbl_Empleado_SIA::join('cat_puestos','tbl_empleados_sia.Puesto','cat_puestos.id_puesto')
->select('No_Empleado','tbl_empleados_sia.Id_Planta', 'No_TAG', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat','tbl_empleados_sia.Puesto','Departamento','Fecha_In')
->where(function ($query) use ($datos_evento) {
    $query->where('No_Empleado', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
        ->orWhere('No_TAG', $datos_evento);
})
->where('Status_Emp', 'A')
->where('Papel','EntPH')
->first();
//dd($Asistencia);
if(!$Asistencia){
    $AsistenciaE = Tbl_Empleado_SIA::select('No_Empleado', 'Id_Planta', 'No_TAG', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat','Puesto', 'Departamento', 'Fecha_In')
    ->where(function ($query) use ($datos_evento) {
        $query->where('No_Empleado', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
            ->orWhere('No_TAG', $datos_evento);
    })
    ->where('Status_Emp', 'A')
    ->first();

    if($AsistenciaE){
        $ing = $AsistenciaE->Fecha_In;
        $auxiliar = 5;
        $nombre = $AsistenciaE->Nom_Emp . ' ' . $AsistenciaE->Ap_Pat . ' ' . $AsistenciaE->Ap_Mat;
        $emplTag = $AsistenciaE->No_Empleado;
    $contador = RegistrarEventos::where('cve_evento', $optionsave)
    ->select('tipo_evento')
    ->get()
    ->first();

$contador = RegistrarEventos::where('cve_evento', $optionsave)
    ->value('tipo_evento');
//dd($contador);
//Este contador evalua tomando el evento seleccionado y que el campo "asistencia" contenga
//el dato mostrado para evaluar la cantidad de registros realizados, mas no el total de personas del Pre Registro
$ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
->where('asistencia', 'Presente')
->where('Planta', 'Intimark1')
->count();
// dd($ConteoRegistroIxtlahuaca);
$RegistroIxtlahuacaTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark1')
->where('Status_Emp', 'A')
->count();
$ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
->where('asistencia', 'Presente')
->where('Planta', 'Intimark2')
->count();
$RegistroSanBartoloTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark2')
->where('Status_Emp', 'A')
->count();
$ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
->where('asistencia', 'Presente')
->count();
$ConteoRegistrosTotal =RegistrarAsistencias::where('tipo_evento', $contador)
->count();
$ConteoRegistrosTotal = $RegistroIxtlahuacaTotal+$RegistroSanBartoloTotal;
$contador = RegistrarEventos::where('cve_evento', $optionsave)
    ->value('tipo_evento');
//Fin de apartado de Contador
    //$this->setMessage('No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
    //return back()->with('error', 'No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
    return view('eventos.ListaEventos', compact('AsistenciaE', 'eventos', 'optionsave', 'auxiliar', 'nombre', 'emplTag',
    'ConteoRegistroIxtlahuaca','ConteoRegistroSanBartolo','ConteoRegistros','ConteoRegistrosTotal',
    'RegistroIxtlahuacaTotal','RegistroSanBartoloTotal','contador','ing'));
    }else{
        $auxiliar = 0;
        $nombre = '';
        $emplTag = '';
        $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->select('tipo_evento')
        ->get()
        ->first();

    $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
    //dd($contador);
    //Este contador evalua tomando el evento seleccionado y que el campo "asistencia" contenga
    //el dato mostrado para evaluar la cantidad de registros realizados, mas no el total de personas del Pre Registro
    $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
    ->where('asistencia', 'Presente')
    ->where('Planta', 'Intimark1')
    ->count();
   // dd($ConteoRegistroIxtlahuaca);
    $RegistroIxtlahuacaTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark1')
    ->where('Status_Emp', 'A')
    ->count();
    $ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
    ->where('asistencia', 'Presente')
    ->where('Planta', 'Intimark2')
    ->count();
    $RegistroSanBartoloTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark2')
    ->where('Status_Emp', 'A')
    ->count();
    $ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
    ->where('asistencia', 'Presente')
    ->count();
    $ConteoRegistrosTotal =RegistrarAsistencias::where('tipo_evento', $contador)
    ->count();
    $ConteoRegistrosTotal = $RegistroIxtlahuacaTotal+$RegistroSanBartoloTotal;
    $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
    //Fin de apartado de Contador
        //$this->setMessage('No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
        //return back()->with('error', 'No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
        return view('eventos.ListaEventos', compact('Asistencia', 'eventos', 'optionsave', 'auxiliar', 'nombre', 'emplTag',
        'ConteoRegistroIxtlahuaca','ConteoRegistroSanBartolo','ConteoRegistros','ConteoRegistrosTotal',
        'RegistroIxtlahuacaTotal','RegistroSanBartoloTotal','contador'));
    }


}
}else{

//$_Asistencia es para traer los datos del empelado de la tabla "tbl_empleados_sia"
$Asistencia = Tbl_Empleado_SIA::select('No_Empleado', 'Id_Planta', 'No_TAG', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat','Puesto', 'Departamento','Fecha_In')
    ->where(function ($query) use ($datos_evento) {
        $query->where('No_Empleado', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
            ->orWhere('No_TAG', $datos_evento);
    })
    ->where('Status_Emp', 'A')
    ->first();
}
    if ($Asistencia) {


        if ($fechaIn = $Asistencia->Fecha_In >= '2023-12-11') {

            $ing = $Asistencia->Fecha_In;
            //dd($ing);
            $nombre = $Asistencia->Nom_Emp . ' ' . $Asistencia->Ap_Pat . ' ' . $Asistencia->Ap_Mat;
            $emplTag = $Asistencia->No_Empleado;
            $auxiliar = 6;


            $contador = RegistrarEventos::where('cve_evento', $optionsave)
            ->select('tipo_evento')
            ->get()
            ->first();

        $contador = RegistrarEventos::where('cve_evento', $optionsave)
            ->value('tipo_evento');
        //dd($contador);
        //Este contador evalua tomando el evento seleccionado y que el campo "asistencia" contenga
        //el dato mostrado para evaluar la cantidad de registros realizados, mas no el total de personas del Pre Registro
        $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'Presente')
        ->where('Planta', 'Intimark1')
        ->count();
       // dd($ConteoRegistroIxtlahuaca);
        $RegistroIxtlahuacaTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark1')
        ->where('Status_Emp', 'A')
        ->count();
        $ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'Presente')
        ->where('Planta', 'Intimark2')
        ->count();
        $RegistroSanBartoloTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark2')
        ->where('Status_Emp', 'A')
        ->count();
        $ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'Presente')
        ->count();
        $ConteoRegistrosTotal =RegistrarAsistencias::where('tipo_evento', $contador)
        ->count();
        $ConteoRegistrosTotal = $RegistroIxtlahuacaTotal+$RegistroSanBartoloTotal;
        $contador = RegistrarEventos::where('cve_evento', $optionsave)
            ->value('tipo_evento');
        //Fin de apartado de Contador
            //$this->setMessage('No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
            //return back()->with('error', 'No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
            return view('eventos.ListaEventos', compact('Asistencia', 'eventos', 'optionsave', 'auxiliar', 'nombre', 'emplTag',
            'ConteoRegistroIxtlahuaca','ConteoRegistroSanBartolo','ConteoRegistros','ConteoRegistrosTotal',
            'RegistroIxtlahuacaTotal','RegistroSanBartoloTotal','contador','ing'));
        }

        $ing = $Asistencia->Fecha_In;
        $depPer = Departamentos::select('id_Departamento', 'Departamento')
            ->where('id_Departamento', $Asistencia->Departamento)
            ->get()
            ->first();

        $consultapuesto = Puestos::all();
        $puesto = Puestos::where('id_puesto', $Asistencia-> Puesto)
        ->select('Puesto')
        ->get()
        ->first();
        //dd($puesto);
        //dd($puesto->Puesto);
        $eventos = RegistrarEventos::where('conf_pre_regis', 'No Requiere Registro')->get();

        $Tip_eventos = RegistrarEventos::where('cve_evento', $request->tipo_evento)
        ->get()
        ->first();
        //dd($Tip_eventos);

        $Tipss_eventos = RegistrarEventos::where('cve_evento', $request->tipo_evento)
        ->select('tipo_evento')
        ->first();

        $Tips_eventos = $Tipss_eventos->tipo_evento;
        //dd($Tips_eventos);
        //apartado para consultar datos existentes
        // Verificar si ya existe un registro para el número de empleado o TAG en el evento actual
        $existingRecord = RegistrarAsistencias::where('tipo_evento', $Tips_eventos)
            ->where(function($query) use ($datos_evento) {
                $query->where('no_empleados', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
                    ->orWhere('No_Tag', $datos_evento);
            })
            ->get()
            ->first();
        //dd($existingRecord);

        $nombre = $Asistencia->Nom_Emp . ' ' . $Asistencia->Ap_Pat . ' ' . $Asistencia->Ap_Mat;
        $emplTag = $Asistencia->No_Empleado;
        $auxiliar = 1;
      //  dd($nombre);
        $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->select('tipo_evento')
        ->get()
        ->first();

    $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
    //dd($contador);
    //Este contador evalua tomando el evento seleccionado y que el campo "asistencia" contenga
    //el dato mostrado para evaluar la cantidad de registros realizados, mas no el total de personas del Pre Registro
    $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
    ->where('asistencia', 'Presente')
    ->where('Planta', 'Intimark1')
    ->count();
   // dd($ConteoRegistroIxtlahuaca);
    $RegistroIxtlahuacaTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark1')
    ->where('Status_Emp', 'A')
    ->count();
    $ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
    ->where('asistencia', 'Presente')
    ->where('Planta', 'Intimark2')
    ->count();
    $RegistroSanBartoloTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark2')
    ->where('Status_Emp', 'A')
    ->count();
    $ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
    ->where('asistencia', 'Presente')
    ->count();
    $ConteoRegistrosTotal =RegistrarAsistencias::where('tipo_evento', $contador)
    ->count();
    $ConteoRegistrosTotal = $RegistroIxtlahuacaTotal+$RegistroSanBartoloTotal;
    //Fin de apartado de Contador
        if ($existingRecord) {
            $auxiliar = 3;
            //dd($auxiliar);
            //$this->setMessage('Este usuario ya está registrado en este evento.');
            $contador = RegistrarEventos::where('cve_evento', $optionsave)
            ->select('tipo_evento')
            ->get()
            ->first();

        $contador = RegistrarEventos::where('cve_evento', $optionsave)
            ->value('tipo_evento');
        //dd($contador);
        //Este contador evalua tomando el evento seleccionado y que el campo "asistencia" contenga
        //el dato mostrado para evaluar la cantidad de registros realizados, mas no el total de personas del Pre Registro
        $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'Presente')
        ->where('Planta', 'Intimark1')
        ->count();
       // dd($ConteoRegistroIxtlahuaca);
        $RegistroIxtlahuacaTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark1')
        ->where('Status_Emp', 'A')
        ->count();
        $ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'Presente')
        ->where('Planta', 'Intimark2')
        ->count();
        $RegistroSanBartoloTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark2')
        ->where('Status_Emp', 'A')
        ->count();
        $ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'Presente')
        ->count();
        $ConteoRegistrosTotal =RegistrarAsistencias::where('tipo_evento', $contador)
        ->count();
        $ConteoRegistrosTotal = $RegistroIxtlahuacaTotal+$RegistroSanBartoloTotal;
        $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
        //Fin de apartado de Contador
            return view('eventos.ListaEventos', compact('Asistencia', 'eventos', 'optionsave', 'auxiliar', 'nombre', 'emplTag',
            'ConteoRegistroIxtlahuaca','ConteoRegistroSanBartolo','ConteoRegistros','ConteoRegistrosTotal',
            'RegistroIxtlahuacaTotal','RegistroSanBartoloTotal','contador','ing'));
        } elseif(!$existingRecord) {

            $Registro = new RegistrarAsistencias();
            $Registro-> id_evento = $Tip_eventos-> id_evento;
            $Registro-> no_empleados = $Asistencia->No_Empleado;
            $Registro-> No_Tag = $Asistencia->No_TAG;
            $Registro-> Planta = $Asistencia->Id_Planta;
            $Registro-> tipo_evento = $Tip_eventos-> tipo_evento;
            $Registro-> Puesto = $puesto->Puesto;
            $Registro-> Departamento = $depPer-> Departamento;
            //$Registro-> PreRegistro= $depPer-> PreRegistro;
            $Registro-> nombre_empleado = $Asistencia -> Nom_Emp . ' ' . $Asistencia->Ap_Pat . ' ' . $Asistencia->Ap_Mat;
            $Registro-> asistencia = 'Presente';
            //dd($Registro-> PreRegistro);
            $Registro-> PreRegistro = $Registro->id . '' . $Asistencia->No_Empleado;
            $Registro-> save();

            $optionsave = $Tip_eventos ? $Tip_eventos->cve_evento : "default";
            $contador = RegistrarEventos::where('cve_evento', $optionsave)
            ->select('tipo_evento')
            ->get()
            ->first();
            $ing = $Asistencia->Fecha_In;
        $contador = RegistrarEventos::where('cve_evento', $optionsave)
            ->value('tipo_evento');
        //dd($contador);
        //Este contador evalua tomando el evento seleccionado y que el campo "asistencia" contenga
        //el dato mostrado para evaluar la cantidad de registros realizados, mas no el total de personas del Pre Registro
        $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'Presente')
        ->where('Planta', 'Intimark1')
        ->count();
       // dd($ConteoRegistroIxtlahuaca);
        $RegistroIxtlahuacaTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark1')
        ->where('Status_Emp', 'A')
        ->count();
        $ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'Presente')
        ->where('Planta', 'Intimark2')
        ->count();
        $RegistroSanBartoloTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark2')
        ->where('Status_Emp', 'A')
        ->count();
        $ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'Presente')
        ->count();
        $ConteoRegistrosTotal =RegistrarAsistencias::where('tipo_evento', $contador)
        ->count();
        $ConteoRegistrosTotal = $RegistroIxtlahuacaTotal+$RegistroSanBartoloTotal;
        $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
        //Fin de apartado de Contador
            return view('eventos.ListaEventos', compact('Asistencia', 'eventos', 'optionsave', 'auxiliar', 'nombre', 'emplTag',
            'ConteoRegistroIxtlahuaca','ConteoRegistroSanBartolo','ConteoRegistros','ConteoRegistrosTotal',
            'RegistroIxtlahuacaTotal','RegistroSanBartoloTotal','contador','ing'));
        }

     }elseif(!$Asistencia) {
        $auxiliar = 0;
        $nombre = '';
        $emplTag = '';
        $ing = null;
        $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->select('tipo_evento')
        ->get()
        ->first();

    $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
    //dd($contador);
    //Este contador evalua tomando el evento seleccionado y que el campo "asistencia" contenga
    //el dato mostrado para evaluar la cantidad de registros realizados, mas no el total de personas del Pre Registro
    $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
    ->where('asistencia', 'Presente')
    ->where('Planta', 'Intimark1')
    ->count();
   // dd($ConteoRegistroIxtlahuaca);
    $RegistroIxtlahuacaTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark1')
    ->where('Status_Emp', 'A')
    ->count();
    $ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
    ->where('asistencia', 'Presente')
    ->where('Planta', 'Intimark2')
    ->count();
    $RegistroSanBartoloTotal = Tbl_Empleado_SIA::where('Id_Planta', 'Intimark2')
    ->where('Status_Emp', 'A')
    ->count();
    $ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
    ->where('asistencia', 'Presente')
    ->count();
    $ConteoRegistrosTotal =RegistrarAsistencias::where('tipo_evento', $contador)
    ->count();
    $ConteoRegistrosTotal = $RegistroIxtlahuacaTotal+$RegistroSanBartoloTotal;
    $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
    //Fin de apartado de Contador
        //$this->setMessage('No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
        //return back()->with('error', 'No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
        return view('eventos.ListaEventos', compact('Asistencia', 'eventos', 'optionsave', 'auxiliar', 'nombre', 'emplTag',
        'ConteoRegistroIxtlahuaca','ConteoRegistroSanBartolo','ConteoRegistros','ConteoRegistrosTotal',
        'RegistroIxtlahuacaTotal','RegistroSanBartoloTotal','contador','ing'));
    }

}



    // Método privado para mostrar mensajes
    private function setMessage($mensaje)
    {
        return flash($mensaje)->success()->important();
    }
    public function PreRegistro(Request $request)
    {

        $eventos = RegistrarEventos::where('conf_pre_regis', 'Requiere Registro')->get();
        //codigo extraido de tabla cat_eventos en la columna cve_evento
        $optionsave = $request->tipo_evento;
        //dd($request->tipo_evento);
        //dato_evento trae el numero de tag o numero de empleado
        $datos_evento = $request->datos_evento;
        //dd($datos_evento);
        // Obten el evento seleccionado
        $evento = RegistrarEventos::where('cve_evento', $optionsave)->first();
        //dd($evento);
        $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
        if (!$evento) {
            return back()->with('error', 'Evento no válido');
        }
        //dd($request->all());
        //$Asistencia = RegistrarAsistencias::all();
        session()->put('datos_evento', $request->tipo_evento);
        $datos_evento = $request->datos_evento;
        if(strlen($datos_evento)==10){
            $datos_evento = substr($datos_evento,-9);
        }
        //$_Asistencia es para traer los datos del empelado de la tabla "tbl_empleados_sia"
        $Asistencia = Tbl_Empleado_SIA::select('No_Empleado', 'Id_Planta', 'No_TAG', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Id_Planta', 'Puesto', 'Departamento')
            ->where(function ($query) use ($datos_evento) {
                $query->where('No_Empleado', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
                    ->orWhere('No_TAG', $datos_evento);
            })
            ->where('Status_Emp', 'A')
            ->first();


        //dd($Asistencia);
        //almacena el numero de empleado desde la tabla "tlb_empleados_sia"
        $emp = Tbl_Empleado_SIA::where('Status_Emp', 'A')
            ->where('no_empleado', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
            ->orWhere('No_TAG', $datos_evento)
            ->select('no_empleado')
            ->first();

        //dd($emp);
        if ($Asistencia) {
            $depPer = Departamentos::select('id_Departamento', 'Departamento')
                ->where('id_Departamento', $Asistencia->Departamento)
                ->get()
                ->first();

            // Obtiene la lista de departamentos permitidos del evento (convertidos a un array)
            $departamentosPermitidos = $depPer-> id_Departamento;

            // Obtiene el departamento del usuario
            $departamentoUsuario = $Asistencia->Departamento;
            // dd($departamentoUsuario);
            // Comprueba si el departamento del usuario está en la lista de departamentos permitidos
            if ($departamentoUsuario != $departamentosPermitidos) {
                return back()->with('error', 'Este usuario no pertenece al departamento');
            } else {


            }
            $consultapuesto = Puestos::all();
            $puesto = Puestos::where('id_puesto', $Asistencia-> Puesto)
            ->select('Puesto')
            ->get()
            ->first();
            //dd($puesto);
            //dd($puesto->Puesto);
            $eventos = RegistrarEventos::where('conf_pre_regis', 'Requiere Registro')->get();

            $Tip_eventos = RegistrarEventos::where('cve_evento', $request->tipo_evento)
            ->get()
            ->first();
            //dd($Tip_eventos);

            $Tipss_eventos = RegistrarEventos::where('cve_evento', $request->tipo_evento)
            ->select('tipo_evento')
            ->first();

            $Tips_eventos = $Tipss_eventos->tipo_evento;
            //dd($datos_evento);
            //apartado para consultar datos existentes
            // Verificar si ya existe un registro para el número de empleado o TAG en el evento actual
            $existingRecord = RegistrarAsistencias::where('tipo_evento', $Tips_eventos)
                ->where(function($query) use ($datos_evento) {
                    $query->where('no_empleados', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
                        ->orWhere('No_Tag', $datos_evento);
                })
                ->get()
                ->first();
            //dd($existingRecord);

            $nombre = $Asistencia->Nom_Emp . ' ' . $Asistencia->Ap_Pat . ' ' . $Asistencia->Ap_Mat;
            $emplTag = $Asistencia->No_Empleado;
            $auxiliar = 1;
            $id_registro = '';
            $evento = RegistrarEventos::where('cve_evento', $optionsave)->first();
            //dd($evento);
            $contador = RegistrarEventos::where('cve_evento', $optionsave)
            ->value('tipo_evento');

            if ($existingRecord) {
                $auxiliar = 3;
                $id_registro = '';
               // $this->setMessage('Este usuario ya está registrado en este evento.');
                return view('eventos.PreRegistro', compact('Asistencia', 'eventos', 'optionsave', 'auxiliar', 'nombre', 'emplTag','contador','id_registro'));
            } elseif(!$existingRecord) {

                $evento = RegistrarEventos::where('cve_evento', $optionsave)->first();
                //dd($evento);
                $contador = RegistrarEventos::where('cve_evento', $optionsave)
                ->value('tipo_evento');
                $Registro = new RegistrarAsistencias();
                $Registro-> id_evento = $Tip_eventos-> id_evento;
                $Registro-> no_empleados = $Asistencia->No_Empleado;
                $Registro-> No_Tag = $Asistencia->No_TAG;
                $Registro-> Planta = $Asistencia->Id_Planta;
                $Registro-> tipo_evento = $Tip_eventos-> tipo_evento;
                $Registro-> Puesto = $puesto->Puesto;
                $Registro-> Departamento = $depPer-> Departamento;
                //$Registro-> PreRegistro= $depPer-> PreRegistro;
                $Registro-> nombre_empleado = $Asistencia -> Nom_Emp . ' ' . $Asistencia->Ap_Pat . ' ' . $Asistencia->Ap_Mat;
                //dd($Registro-> PreRegistro);

                $Registro-> save();
                $Registro-> PreRegistro = $Registro->id . '' . $Asistencia->No_Empleado;
                $Registro-> save();

                $optionsave = $Tip_eventos ? $Tip_eventos->cve_evento : "default";
                return view('eventos.PreRegistro', compact('Asistencia', 'eventos', 'optionsave', 'auxiliar', 'nombre', 'emplTag','contador','id_registro'));
            }

        } else {
            $auxiliar = 0;
            $nombre = '';
            $emplTag = '';
            $id_registro = '';
            $evento = RegistrarEventos::where('cve_evento', $optionsave)->first();
            //dd($evento);
            $contador = RegistrarEventos::where('cve_evento', $optionsave)
            ->value('tipo_evento');
            //$this->setMessage('No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
            //return back()->with('error', 'No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
            return view('eventos.PreRegistro', compact('Asistencia', 'eventos', 'optionsave', 'auxiliar', 'nombre', 'emplTag','contador','id_registro'));
        }

    }

    public function RegistroAsistencias(Request $request)
    {
        $eventos = RegistrarEventos::where('conf_pre_regis', 'Requiere Registro')->get();
        $optionsave = $request->tipo_evento;
        //dd($optionsave);
        $datos_evento = $request->datos_evento;
        if(strlen($datos_evento)==10){
            $datos_evento = substr($datos_evento,-9);
        }
            //dd($datos_evento);
        $catEvento = RegistrarEventos::where('cve_evento', $request->tipo_evento)->first();
        $optionsave = $catEvento->tipo_evento;
        $TablaEventos = RegistrarAsistencias::all();
            // Obten el evento seleccionado
        $TablaEventos = RegistrarAsistencias::select('id', 'id_evento', 'no_empleados', 'No_Tag', 'tipo_evento', 'nombre_empleado', 'Puesto', 'Planta', 'PreRegistro', 'Departamento', 'asistencia')
        ->where('tipo_evento', $optionsave)
        ->where(function ($query) use ($datos_evento) {
            $query->where('no_empleados', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
                ->orWhere('No_Tag', $datos_evento);
        })
        ->first();

        $empleado = Tbl_Empleado_SIA::where('No_Empleado', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
        ->orWhere('No_TAG', $datos_evento)
        ->first();
        //Se agrego esta seccion de codigo debido a que se necesita solicitar el codigo del evento
        //y ese apartado en la tabla 'eventos' no cuenta con ese valor
        $Tip_eventos = RegistrarEventos:: where('cve_evento',$request->tipo_evento)
                ->get()
                ->first();


        $optionsave = $Tip_eventos ? $Tip_eventos->cve_evento : "default";
        // fin del codigo estraido

        //inicio de apartado de Contador
        $contador = RegistrarEventos::where('cve_evento', $optionsave)
            ->value('tipo_evento');
        //Fin de apartado de Contador
        $Asistencia = Tbl_Empleado_SIA::select('No_Empleado', 'Id_Planta', 'No_TAG', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat', 'Id_Planta', 'Puesto', 'Departamento')
            ->where(function ($query) use ($datos_evento) {
                $query->where('No_Empleado', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
                    ->orWhere('No_TAG', $datos_evento);
            })
            ->where('Status_Emp', 'A')
            ->first();
            if(!$Asistencia || !$TablaEventos ){
                $nombre = " ";
                $emplTag = " ";
                $auxiliar = 0;
                $ConteoRegistroIxtlahuaca = '';
                $RegistroIxtlahuacaTotal = '';
                $ConteoRegistroSanBartolo = '';
                $RegistroSanBartoloTotal  = '';
                $ConteoRegistros = '';
                $ConteoRegistrosTotal ='';
                $contador = RegistrarEventos::where('cve_evento', $optionsave)
                ->value('tipo_evento');
                $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
                ->where('asistencia', 'asistencia confirmada')
                ->where('Planta', 'Intimark1')
                ->count();
               // dd($ConteoRegistroIxtlahuaca);
                $RegistroIxtlahuacaTotal = RegistrarAsistencias::where('tipo_evento', $contador)
                ->where('Planta', 'Intimark1')
                ->count();
           // dd($RegistroIxtlahuacaTotal);
                $ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
                ->where('asistencia', 'asistencia confirmada')
                ->where('Planta', 'Intimark2')
                ->count();
                $RegistroSanBartoloTotal = RegistrarAsistencias::where('tipo_evento', $contador)
                ->where('Planta', 'Intimark2')
                ->count();
                $ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
                ->where('asistencia', 'asistencia confirmada')
                ->count();
                $ConteoRegistrosTotal =RegistrarAsistencias::where('tipo_evento', $contador)
                ->count();
                $ConteoRegistrosTotal=$RegistroIxtlahuacaTotal+$RegistroSanBartoloTotal;
                return view('eventos.RegistroAsistencias', compact('Asistencia','optionsave','auxiliar','nombre','emplTag','eventos',
                'ConteoRegistroIxtlahuaca','RegistroIxtlahuacaTotal','ConteoRegistroSanBartolo','RegistroSanBartoloTotal','ConteoRegistros','ConteoRegistrosTotal','contador'));
            }
        $nombre = $Asistencia->Nom_Emp . ' ' . $Asistencia->Ap_Pat . ' ' . $Asistencia->Ap_Mat;
            $emplTag = $Asistencia->No_Empleado;
            $auxiliar = 1;


        if ($TablaEventos && ($TablaEventos->asistencia == NULL)){
            $TablaEventos->update(['asistencia' => 'asistencia confirmada']);
            //apartado para realizar un conteo de acuerdo a la consulta

        $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
        $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'asistencia confirmada')
        ->where('Planta', 'Intimark1')
        ->count();
       // dd($ConteoRegistroIxtlahuaca);
        $RegistroIxtlahuacaTotal = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('Planta', 'Intimark1')
        ->count();
   // dd($RegistroIxtlahuacaTotal);
        $ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'asistencia confirmada')
        ->where('Planta', 'Intimark2')
        ->count();
        $RegistroSanBartoloTotal = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('Planta', 'Intimark2')
        ->count();
        $ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'asistencia confirmada')
        ->count();
        $ConteoRegistrosTotal =RegistrarAsistencias::where('tipo_evento', $contador)
        ->count();
        $ConteoRegistrosTotal=$RegistroIxtlahuacaTotal+$RegistroSanBartoloTotal;
        //Fin de apartado de Contador


            //return back()->with('error', 'No se encontraron datos para el número de empleado o TAG proporcionado. Por favor, inténtalo de nuevo.');
            return view('eventos.RegistroAsistencias', compact('eventos','TablaEventos', 'optionsave', 'empleado',
                'auxiliar', 'nombre', 'emplTag',
                'ConteoRegistroIxtlahuaca','ConteoRegistroSanBartolo','ConteoRegistros','ConteoRegistrosTotal',
                'RegistroIxtlahuacaTotal','RegistroSanBartoloTotal','contador'));
        }else{
            $nombre = $Asistencia->Nom_Emp . ' ' . $Asistencia->Ap_Pat . ' ' . $Asistencia->Ap_Mat;
            $emplTag = $Asistencia->No_Empleado;
    $auxiliar = 3;

        $contador = RegistrarEventos::where('cve_evento', $optionsave)
        ->value('tipo_evento');
        $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'asistencia confirmada')
        ->where('Planta', 'Intimark1')
        ->count();
       // dd($ConteoRegistroIxtlahuaca);
        $RegistroIxtlahuacaTotal = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('Planta', 'Intimark1')
        ->count();
   // dd($RegistroIxtlahuacaTotal);
        $ConteoRegistroSanBartolo = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'asistencia confirmada')
        ->where('Planta', 'Intimark2')
        ->count();
        $RegistroSanBartoloTotal = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('Planta', 'Intimark2')
        ->count();
        $ConteoRegistros = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'asistencia confirmada')
        ->count();
        $ConteoRegistrosTotal =RegistrarAsistencias::where('tipo_evento', $contador)
        ->count();
        $ConteoRegistrosTotal=$RegistroIxtlahuacaTotal+$RegistroSanBartoloTotal;


    return view('eventos.RegistroAsistencias', compact(
        'eventos',
        'TablaEventos',
        'optionsave',
        'empleado',
        'auxiliar',
        'nombre',
        'emplTag',
        'catEvento',
        'ConteoRegistroIxtlahuaca',
        'ConteoRegistroSanBartolo',
        'ConteoRegistros',
        'ConteoRegistrosTotal',
        'RegistroIxtlahuacaTotal',
        'RegistroSanBartoloTotal',
        'contador'
    ));

}    }

    }

