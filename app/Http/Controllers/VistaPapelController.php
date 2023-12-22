<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RegistrarAsistencias;
use App\RegistrarEventos;
use App\Tbl_Empleado_SIA;

class VistaPapelController extends Controller
{
    public function VistaPapel(){ 

        $contador = RegistrarEventos::where('cve_evento', 'EntPH')
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
        $contador = RegistrarEventos::where('cve_evento', 'EntPH')
            ->value('tipo_evento');


        return view('eventos.VistaPapel', compact(
        'ConteoRegistroIxtlahuaca','ConteoRegistroSanBartolo','ConteoRegistros','ConteoRegistrosTotal',
        'RegistroIxtlahuacaTotal','RegistroSanBartoloTotal','contador'));
    }

    public function RegistroVistaPapel(Request $request){
        

        $optionsave = 'EntPH';
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
        //dd($request, $Asistencia);
        session()->put('datos_evento', $request->tipo_evento);
        $datos_evento = $request->datos_evento;
        if(strlen($datos_evento)==10){
            $datos_evento = substr($datos_evento,-9);
        }
        

            $AsistenciaE = Tbl_Empleado_SIA::select('No_Empleado', 'Id_Planta', 'No_TAG', 'Nom_Emp', 'Ap_Pat', 'Ap_Mat','Puesto', 'Departamento', 'Fecha_In')
            ->where(function ($query) use ($datos_evento) {
                $query->where('No_Empleado', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
                    ->orWhere('No_TAG', $datos_evento);
            })
            ->where('Status_Emp', 'A')
            ->first();


                $ing = $AsistenciaE->Fecha_In;
                $auxiliar = 5;
                $nombre = $AsistenciaE->Nom_Emp . ' ' . $AsistenciaE->Ap_Pat . ' ' . $AsistenciaE->Ap_Mat;
                $emplTag = $AsistenciaE->No_Empleado;
            // Obtén el contador y actualiza el ConteoRegistroIxtlahuaca antes de retornar la vista
    $contador = RegistrarEventos::where('cve_evento', 'EntPH')->value('tipo_evento');
    $ConteoRegistroIxtlahuaca = RegistrarAsistencias::where('tipo_evento', $contador)
        ->where('asistencia', 'Presente')
        ->where('Planta', 'Intimark1')
        ->count();
        return view('eventos.VistaPapel' , compact(
            'ConteoRegistroIxtlahuaca',
            'contador'));
    }
    
}