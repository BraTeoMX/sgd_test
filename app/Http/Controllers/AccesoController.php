<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Laracasts\Flash\Flash;
use App\User;
use App\Helper;
use Auth;
use Artisan;


class AccesoController extends Controller
{

    public function index()
    {

         $roles = Role::all();
        $permisos = Permission::orderBy('id','DESC')->get();
        return view('accesos.index', compact('roles','permisos'));
    }

    public function store(Request $request)
    {
      
      $roles=Role::orderBy('id','DESC')->get();
      foreach ($roles as $rol) {
        $nombre_rol = str_replace(" ","_",$rol->name);
        $rol->syncPermissions($request[$nombre_rol]);
      }

        flash("Los permisos han sido actualizados")->important();
        return redirect('/acceso');
    }

    public function show($rol)
    {
      $rol= Role::where('id',$rol)->get();
      $permisos = Permission::orderBy('id','DESC')->get();
      return view('accesos.index', compact('permisos','rol'));
    }
}
