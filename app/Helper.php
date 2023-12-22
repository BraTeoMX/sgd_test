<?php
use Illuminate\Http\Request;
use App\Role;
use App\Municipio;
use App\Localidad;

//retorno de mensajes en el proyecto
// function setMessage($mensaje)
// {
//     return  flash($mensaje)->success()->important();
// }

function isRolSuperior()
{
    $rol = Role::where('id', '<', auth()->user()->roles()->first()->id)->first();
    return (filled($rol));
}

//Retorna el nombre del municipio segun id_estado e id_municipio
// function nombreMunicipio($estado,$municipio)
// {
//    //    dd($municipio);
//     $municipios = Municipio::where([
//         ['estado_id', '=', $estado],
//         ['id', '=', $municipio],
//     ])->get();


//     return (count($municipios)>0) ? $municipios[0]->municipio : "";
// }

//Retorna el nombre de la colonia segun id_estado, id_municipio, id_colonia
// function nombreColonia($colonia,$estado,$municipio)
// {
//     $localidad = Localidad::where([
//         ['estado_id', '=', $estado],
//         ['municipio_id', '=', $municipio],
//         ['id', '=', $colonia],
//     ])->get();

//     return (count($localidad)>0) ? $localidad[0]->localidad : "";
// }
