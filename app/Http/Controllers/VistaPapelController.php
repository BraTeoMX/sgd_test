<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RegistrarAsistencias;
use App\RegistrarEventos;
use App\Tbl_Empleado_SIA;
use App\RegistroPapelTemporal;


class VistaPapelController extends Controller
{
    public function VistaPapel(){ 

        $conteos = RegistroPapelTemporal::where('asistencia', 'Presente')
            ->whereIn('Planta', ['Intimark1', 'Intimark2'])
            ->groupBy('Planta')
            ->selectRaw('Planta, count(*) as total')
            ->get()
            ->keyBy('Planta'); // Organiza los resultados por planta para un acceso más fácil

        // Ahora, puedes obtener los conteos por planta:
        $ConteoRegistroIxtlahuaca = $conteos['Intimark1']->total ?? 0;
        $ConteoRegistroSanBartolo = $conteos['Intimark2']->total ?? 0;

        $ConteoRegistros = $ConteoRegistroIxtlahuaca + $ConteoRegistroSanBartolo;
        return view('eventos.VistaPapel', compact(
        'ConteoRegistroIxtlahuaca','ConteoRegistroSanBartolo',
        'ConteoRegistros'));
    }

    public function RegistroVistaPapel(Request $request){
        //dato_evento trae el numero de tag o numero de empleado
        $datos_evento = $request->datos_evento;
        // Obten el evento seleccionado
        $evento = RegistrarEventos::where('cve_evento', 'EntPH')->first();
        //dd($evento);
        $AsistenciaE = Tbl_Empleado_SIA::with(['departamentoRelacionado', 'puestoRelacionado'])
            ->where(function ($query) use ($datos_evento) {
                $query->where('No_Empleado', str_pad($datos_evento, 7, '0', STR_PAD_LEFT))
                    ->orWhere('No_TAG', $datos_evento);
            })
            ->where('Status_Emp', 'A')
            ->first();
        $nombre = $AsistenciaE->Nom_Emp . ' ' . $AsistenciaE->Ap_Pat . ' ' . $AsistenciaE->Ap_Mat;
        $departamento = $AsistenciaE->departamentoRelacionado->Departamento ?? 'Departamento por defecto';
        $puesto = $AsistenciaE->puestoRelacionado->Puesto ?? 'Puesto por defecto';
        if(!$AsistenciaE) {
            // Redirigir con un mensaje de error
            return redirect()->route('eventos.VistaPapel')->with('auxiliar', 0);

        }
        //dd($AsistenciaE);
        // Verificar si ya existe un registro en RegistroPapelTemporal
        $registroExistente = RegistroPapelTemporal::where('no_empleados', $AsistenciaE->No_Empleado)
        ->where('No_Tag', $AsistenciaE->No_TAG)
        ->where('id_evento', $evento->id)
        ->first();
        
        if ($registroExistente) {
        // Si ya existe un registro, redirigir con un mensaje de que ya existe
            
            return redirect()->route('eventos.VistaPapel')->with('auxiliar', 2)->with('datos', $nombre);
        }
        // Verificar si encontramos un registro
        if ($AsistenciaE->puestoRelacionado && $AsistenciaE->puestoRelacionado->Papel == 'EntPH') {
            // Crear un nuevo registro en la tabla usando el modelo RegistroPapelTemporal
            RegistroPapelTemporal::create([
                'id_evento' => $evento->id, // Asumiendo que $evento tiene un atributo 'id'
                'no_empleados' => $AsistenciaE->No_Empleado,
                'No_Tag' => $AsistenciaE->No_TAG,
                'tipo_evento' => $evento->tipo_evento, // O $optionsave si tipo_evento no está en el modelo de evento
                'nombre_empleado' => $nombre,
                'Departamento' => $departamento,
                'Puesto' => $puesto,
                'asistencia' => 'Presente', // Si estás marcando la asistencia como presente
                'Planta' => $AsistenciaE->Id_Planta,
                // Las columnas 'created_at' y 'updated_at' se llenan automáticamente si tu modelo usa timestamps
            ]);

            // Redirigir con un mensaje de éxito
            return redirect()->route('eventos.VistaPapel')->with('auxiliar', 1)->with('datos', $nombre);

        } if($AsistenciaE){
            // Redirigir con un mensaje referente a que el puesto no recibe papel   
            return redirect()->route('eventos.VistaPapel')->with('auxiliar', 3)->with('datos', $nombre);
        }
        
    }
    
}