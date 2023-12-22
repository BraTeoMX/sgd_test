@extends('layouts.main')

@section('styleBFile')

<!-- Color Box -->
<link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">

@endsection

@section('content')
    @php
        $i=0;
    @endphp
    @forelse($vacaciones as $vacacion)
        <h2>Liberar vacaciones de: {!! $vacacion->No_Empleado !!} </h2>
        @php
            $i++;
        @endphp
        <div class="row">    
            <div class="col-md-2">                                
                {!! BootForm::text('nombre', 'Folio: *', $vacacion->folio_vac, ['width'=>'col-md-6','readonly']); !!}
            </div>
            <div class="col-md-2">                                
                {!! BootForm::text('turno', 'Fecha: *', $vacacion->fecha_solicitud, ['width'=>'col-md-3','readonly']); !!}
            </div>
            <div class="col-md-2">                                
                {!! BootForm::text('area', 'Inicio Vacaciones : *', $vacacion->fech_ini_vac, ['width'=>'col-md-3','readonly']); !!}
            </div>
            <div class="col-md-2">                                
                {!! BootForm::text('frecuencia', 'Fin Vacaciones: *', $vacacion->fech_fin_vac, ['width'=>'col-md-3','readonly']); !!}
            </div>
            <div class="col-md-4">                                
                {!! BootForm::text('dias_disponibles', 'Nombre: *', $vacacion->fk_no_empleado.' '.$vacacion->Ap_Pat.' '.$vacacion->Ap_Mat, ['width'=>'col-md-3','readonly']); !!}
            </div>
            <div class="col-md-2">                                
                {!! BootForm::text('dias_disponibles', 'Puesto: *', $vacacion->Puesto, ['width'=>'col-md-3','readonly']); !!}
            </div>
            <div class="col-md-2">                                
                {!! BootForm::text('dias_disponibles', 'Departamento: *', $vacacion->Departamento, ['width'=>'col-md-3','readonly']); !!}
            </div>
        </div>
    @empty
    @endforelse
    <div class="raw">
        <div class="col center">
            <a class="btn btn-success" href="{!! route('vacaciones.liberarPermiso',$vacacion->folio_vac)!!}">LIBERAR VACACIONES</a>
        </div>        
    </div>
@endsection