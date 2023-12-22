<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Spatie\Permission\Models\Responsables;
//use Spatie\Permission\Models\Incidencias;

use Laracasts\Flash\Flash;
use App\User;
use App\Responsables;
use App\Incidencia;
use App\CatalogoPermisos;
use App\ValorPermiso;
use App\Helper;
use Auth;
use Artisan;


class AutorizacionController extends Controller
{

    public function index()
    {
         $permisos = CatalogoPermisos::all();
        
         $valorpermisos = ValorPermiso::all();
        return view('autorizacion.index', compact('permisos','valorpermisos'));
    }

    
    public function store(Request $request)
    {
     
dd($request->input(1)->input(firma_vp));

      $permisos=CatalogoPermisos::all();
      $valorpermisos = ValorPermiso::all();

      for ($i=0;$i<CatalogoPermisos::count();$i++){
        $valor1 =$request[1]->firma_vp ;
        print_r($valor1);
        for ($j=0;$j<ValorPermiso::count();$j++){
          if(isset($request[$i+1]) ){
        //    echo $request[$i+1];
         //   CatalogoPermisos::WHERE('id_permiso',$permisos[$i]->id_permiso)->where($valorpermisos[$j],)->UPDATE(['permiso_jefe' => 1, 'status' => 'APLICADO', 'fecha_aprobacion' => date("Y-m-d"), 'excepcion' => $per->excepcion]);
          }  
        }      
        
      }  
      dd("ok");
      foreach ($valorpermisos as $valor){

      dd($valor->cve_valorpermiso);

    }

      for ($i=0;$i<CatalogoPermisos::count();$i++){
             
        if(isset($request[$i+1])){
          CatalogoPermisos::WHERE('id_permiso', $i+1)->UPDATE(['permiso_jefe' => 1, 'status' => 'APLICADO', 'fecha_aprobacion' => date("Y-m-d"), 'excepcion' => $per->excepcion]);
          dd($permisos[$i]->id_permiso);
        }
      }
      

        flash("La autorizacion ha sido actualizada")->important();
        return redirect('/autorizacion');
    }

    public function show($permiso)
    {
      dd($permiso);
      $permisos= CatalogoPermisos::where('id',$permiso)->get();
      $valorpermisos = ValorPermiso::orderBy('id','DESC')->get();
      return view('autorizacion.index', compact('valorpermisos','permisos'));
    }
}
