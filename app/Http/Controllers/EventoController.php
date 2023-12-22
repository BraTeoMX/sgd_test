<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\RegistrarEventos;
use App\RegistrarAsistencias;
use App\Tbl_Empleado_SIA;

use App\Puestos;

class EventoController extends Controller
{
    // Método para listar eventos
    public function ListaEventos(Request $request)
    {
        // Obtiene eventos que no requieren preregistro

        $eventos = RegistrarEventos::where('conf_pre_regis', 'No Requiere Registro')->get();

        // Establece una opción de ejemplo (puedes cambiarla según tus necesidades)
        $optionsave = 1;
        $auxiliar = 2;
        // Obtiene el tipo de evento asociado a la opción
        $countAsis = RegistrarEventos::where('cve_evento', $optionsave)->value('tipo_evento');
        $Asistencia = Tbl_Empleado_SIA::first();
        $nombre = '';
        $emplTag = '';
        $ConteoRegistroIxtlahuaca = 0;
        $ConteoRegistroSanBartolo = 0;
        $ConteoRegistros = 0;
        $contador = ' ';
        $ConteoRegistrosTotal = 0;
        $RegistroIxtlahuacaTotal = 0;
        $RegistroSanBartoloTotal = 0;
        $ing = '';
        // Cuenta el número de registros de asistencia para el evento
        $conteoRegistros = RegistrarAsistencias::where('tipo_evento', $countAsis)
            ->whereNull('asistencia')
            ->count();
        //dd($countAsis);
        // Devuelve la vista 'eventos.ListaEventos' con datos
        return view('eventos.ListaEventos', compact(
            'eventos',
            'optionsave',
            'conteoRegistros',
            'countAsis',
            'auxiliar',
            'Asistencia',
            'nombre',
            'emplTag',
            'ConteoRegistroIxtlahuaca',
            'ConteoRegistroSanBartolo',
            'ConteoRegistros',
            'ConteoRegistrosTotal',
            'RegistroIxtlahuacaTotal',
            'RegistroSanBartoloTotal',
            'contador',
            'ing'
        ));
    }




    public function PreRegistro()
    {
        $eventos = RegistrarEventos::where('conf_pre_regis', 'Requiere Registro')->get();
        $optionsave = 1;
        $nombre = '';
        $emplTag = '';
        $auxiliar = 2;
        $contador =  '';
        $id_registro = '';
        return view('eventos.PreRegistro', compact('eventos', 'optionsave', 'auxiliar', 'nombre', 'emplTag', 'contador', 'id_registro'));
    }

    public function RegistroAsistencias()
    {
        $eventos = RegistrarEventos::where('conf_pre_regis', 'Requiere Registro')->get();
        $optionsave = 1;
        $nombre = '';
        $emplTag = '';
        $auxiliar = 2;
        $ConteoRegistroIxtlahuaca = 0;
        $ConteoRegistroSanBartolo = 0;
        $ConteoRegistros = 0;
        $ConteoRegistrosTotal = 0;
        $RegistroIxtlahuacaTotal = 0;
        $RegistroSanBartoloTotal = 0;
        $contador =  '';
        $idregistro =   '';
        return view('eventos.RegistroAsistencias', compact(
            'eventos',
            'optionsave',
            'auxiliar',
            'nombre',
            'emplTag',
            'ConteoRegistroIxtlahuaca',
            'ConteoRegistroSanBartolo',
            'ConteoRegistros',
            'ConteoRegistrosTotal',
            'RegistroIxtlahuacaTotal',
            'RegistroSanBartoloTotal',
            'contador',
            'idregistro'
        ));
    }

    // Método para mostrar el formulario de creación de eventos
    public function create()
    {
        // Obtiene todos los eventos registrados
        $eventos = RegistrarEventos::all();


        // Devuelve la vista 'eventos.create' con datos
        //  dd($puestos);
        return view('eventos.create', compact('eventos'));
    }
    public function UpdatePuestos()
    {
        $puestos = Puestos::all();

        return view('eventos.UpdatePuestos', compact('puestos'));

    }
    public function actualizarPapel(Request $request)
    {
        try {
            // Obtener los IDs seleccionados desde la solicitud
            $selectedIds = $request->input('selectedIds');

            // Actualizar los registros en la base de datos
            Puestos::whereNotIn('id', $selectedIds)->update(['Papel' => null]);
            Puestos::whereIn('id', $selectedIds)->update(['Papel' => 'EntPH']);

            // Respuesta exitosa
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Manejar errores
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // Método para registrar nuevos eventos
    public function RegistrarEventos(Request $request)
    {
        // Recopila los datos del formulario
        $nombreEvento = $request->input('nombre_evento');
        $requierePreRegistros = $request->has('requiere_pre_registros'); // Verifica si el checkbox está marcado

        // Calcula el próximo valor para "id_evento"
        $ultimoEvento = RegistrarEventos::orderBy('id_evento', 'desc')->first();
        $idEvento = $ultimoEvento ? $ultimoEvento->id_evento + 1 : 5; // Empezar en 5 si es el primer evento

        // Genera un código corto (cve_evento) a partir del nombre del evento
        $cve_evento = '';
        foreach (str_word_count($nombreEvento, 1) as $palabra) {
            $cve_evento .= substr($palabra, 0, 2); // Agrega la primera letra
            $cve_evento .= substr($palabra, -2);    // Agrega la última letra
        }

        // Crea un nuevo evento
        $nuevoEvento = new RegistrarEventos();
        $nuevoEvento->id_evento = $idEvento;
        $nuevoEvento->cve_evento = $cve_evento . $idEvento;
        $nuevoEvento->tipo_evento = $nombreEvento;
        $nuevoEvento->conf_pre_regis = $requierePreRegistros ? 'Requiere Registro' : 'No Requiere Registro';
        $nuevoEvento->save();

        // Redirige a la página de creación de eventos con un mensaje de éxito
        return redirect()->route('eventos.create');
    }

    // Método para eliminar eventos
    public function destroy(RegistrarEventos $evento)
    {
        // Realiza la lógica de eliminación aquí (en este caso, elimina un evento)
        $evento->delete();
        //dd($evento);
        // Redirige a la página de creación de eventos con un mensaje de éxito
        return redirect()->route('eventos.create');
    }

    // En tu EventoController.php


    public function DeleteRegistros(Request $request)
    {
        $cve_evento = $request->input('tipo_evento');
        $eventos = RegistrarEventos::where('conf_pre_regis', 'Requiere Registro')->get();
        $optionsave = 1;
        $TipEvent = RegistrarEventos::where('cve_evento', $cve_evento)
            ->value('tipo_evento');
        $registros = RegistrarAsistencias::select('id', 'no_empleados', 'No_Tag', 'tipo_evento', 'nombre_empleado')
            ->where('tipo_evento', $TipEvent)
            ->get();
        $reportesQuery = RegistrarAsistencias::select('id', 'id_evento', 'no_empleados', 'No_Tag', 'tipo_evento', 'nombre_empleado', 'Puesto', 'created_at', 'asistencia', 'Planta', 'Departamento')
        ->where('tipo_evento', $TipEvent)
        ->get();
        $totalRegistros = $reportesQuery->count();


        return view('eventos.DeleteRegistros', compact('eventos', 'optionsave', 'registros', 'TipEvent', 'totalRegistros'));
    }
    public function BorrarRegistro(RegistrarAsistencias $registro)
    {

        $registro->delete();
        //  dd($registro);
        return redirect()->route('eventos.DeleteRegistros');
    }
    public function DeleteRegPre(Request $request)
    {
        $cve_evento = $request->input('tipo_evento');
        $eventos = RegistrarEventos::where('conf_pre_regis', 'No Requiere Registro')->get();
        $optionsave = 1;
        $TipEvent = RegistrarEventos::where('cve_evento', $cve_evento)
            ->value('tipo_evento');
        $registros = RegistrarAsistencias::select('id', 'no_empleados', 'No_Tag', 'tipo_evento', 'nombre_empleado')
            ->where('tipo_evento', $TipEvent)
            ->get();
        $reportesQuery = RegistrarAsistencias::select('id', 'id_evento', 'no_empleados', 'No_Tag', 'tipo_evento', 'nombre_empleado', 'Puesto', 'created_at', 'asistencia', 'Planta', 'Departamento')
        ->where('tipo_evento', $TipEvent)
        ->get();
        $totalRegistros = $reportesQuery->count();
        return view('eventos.DeleteRegPre', compact('eventos', 'optionsave', 'registros', 'TipEvent', 'totalRegistros'));
    }
    public function BorrarRegistroPre(RegistrarAsistencias $registro)
    {

    $registro->delete();
    //  dd($registro);
    return redirect()->route('eventos.DeleteRegPre');
}

}
