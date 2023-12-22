<?php

namespace App\Http\Controllers;

use App\Exports\PermisosExport;
use App\Exports\UsersExport;
use App\FormatoPermisos;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AsiswebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        #return Excel::download(new UsersExport, 'users.csv');
        /*return Excel::download(new UsersExport, 'users.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);*/
        #return (new UsersExport(366,365))->download('users.csv');
        return view('asisweb.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $fecha1 = $request->inicio_fecha;
        $fecha2 = $request->fin_fecha;
        $motivo = $request->tipo;
        if ($motivo === 'JustiHoras') {
            return (new PermisosExport($fecha1, $fecha2, $motivo))->download('JustiHoras.csv');
        } elseif ($motivo === 'JustiDia') {
            return (new PermisosExport($fecha1, $fecha2, $motivo))->download('JustiDia.csv');
        } elseif ($motivo === 'JustiVac') {
            return (new PermisosExport($fecha1, $fecha2, $motivo))->download('JustiVac.csv');
        } elseif ($motivo === 'JustiFalta') {
            return (new PermisosExport($fecha1, $fecha2, $motivo))->download('JustiFalta.csv');
        } elseif ($motivo === 'JustiInca') {
            return (new PermisosExport($fecha1, $fecha2, $motivo))->download('JustiInca.csv');
        }else{
            return view('asisweb.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
}
