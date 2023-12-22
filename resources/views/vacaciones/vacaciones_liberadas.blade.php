@extends('layouts.main')

@section('styleBFile')

<!-- Color Box -->
<link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">

@endsection

@section('content')

<div class="row">   
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vacaciones Autorizadas</h3>
            </div>

          <!-- <div class="row">
                <a class="m-2 col-2 btn btn-info" href="{!! route('vacaciones.solicitarvacaciones') !!}" class="btn btn-info float-middle ml-4">Solicitar Vacaciones</a>
                <a class="m-2 col-2 btn btn-success" href="{!! url('/vacaciones') !!}" class="btn btn-info float-middle ml-4">Solicitudes De Vacaciones</a>
            </div> -->            

            <div class="card-body">

                {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
                <div class="form-group">   
                    <div class="col-6 input-group">
                        <label class="text-dark" style="line-height: 2;"> No. de Empleado :&nbsp;</label>
                        <input type="search" class="form-control col-8" id="no_empleado" name="no_empleado">
                        <span class="col-2">
                            {!! Form::submit('Buscar', ['class' => 'btn btn-light']); !!}                            
                        </span>
                    </div>                    
                </div> 
                {!! BootForm::close() !!}
                 
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Inicio Vacaciones</th>
                            <th>Fin Vacaciones</th>
                            <th>No. Empleado</th>
                            <th>Nombre</th>
                            <th>Puesto</th>
                            <th>Departamento</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($vacaciones as $vacacion)
                                <tr>
                                    <td>{{$vacacion->folio_vac}}</td>
                                    <td>{{$vacacion->fecha_solicitud}}</td>
                                    <td>{{$vacacion->fech_ini_vac}}</td>
                                    <td>{{$vacacion->fech_fin_vac}}</td>
                                    <td>{{$vacacion->fk_no_empleado}}</td>
                                    <td>{{$vacacion->Nom_Emp.' '.$vacacion->Ap_Pat.' '.$vacacion->Ap_Mat}}</td>
                                    <td>{{$vacacion->Puesto}}</td>
                                    <td>{{$vacacion->Departamento}}</td>
                                    <td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div> 

@endsection