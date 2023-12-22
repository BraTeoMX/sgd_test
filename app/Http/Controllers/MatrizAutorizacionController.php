<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Spatie\Permission\Models\Responsables;
//use Spatie\Permission\Models\Incidencias;
use Laracasts\Flash\Flash;
use App\User;
use App\Responsables;
use App\Puestos;
use App\EstadoNegocio;


use App\Incidencia;
use App\Helper;
use Auth;
use Artisan;


class MatrizAutorizacionController extends Controller
{

    public function index()
    {
         $responsables = Puestos::where('Nivel','<>',10)->where('id_puesto','<>',1)->orderBy('Puesto','Desc')->get();
        $incidencias = estadoNegocio::orderBy('id')->get();
        return view('matrizautorizacion.index', compact('responsables','incidencias'));
    }

    public function store(Request $request)
    {
      
      $responsables=Responsables::orderBy('id','DESC')->get();
      foreach ($responsables as $responsable) {
        $nombre_responsable = str_replace(" ","_",$responsable->name);
        $responsable->syncPermissions($request[$nombre_responsable]);
      }

        flash("La autorizacion ha sido actualizada")->important();
        return redirect('/autorizacion');
    }

    public function show($responsable)
    {
      $responsables= Responsable::where('id',$responsable)->get();
      $incidencias = Incidencias::orderBy('id','DESC')->get();
      return view('autorizacion.index', compact('incidencias','responsables'));
    }
}
