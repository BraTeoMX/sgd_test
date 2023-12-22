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
                <h3 class="card-title">Formulario de solicitud de vacaciones</h3>
            </div>
            {!! Form::open(['route'=>'buscarEmp', 'method'=>'GET', 'files'=>TRUE ]) !!}
            <div>
                <input type="text" name="noEmpleado" placeholder="Numero de empleado">
            </div>
            <div>
                <input type="submit" value="Buscar" class="bt btn-primary">
            </div>
            {!! form::close() !!}
        </div>
    </div>
</div> 

@endsection

