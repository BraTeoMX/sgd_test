@extends('layouts.main')

@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3>Incapacidad</h3>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-body">
            @isset($incapacidad)
                @forelse ($incapacidad as $faltaj)
                    {!! Form::open(['route' => 'editarincapacidad', 'method' => 'post', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-md-2">
                            {!! BootForm::hidden('id', $faltaj->id) !!}
                            {!! BootForm::text('folio', 'Folio ', $faltaj->folio_incapacidad, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                        <div class="col-md-2">
                            {!! BootForm::text('no_emp', 'No. EMPLEADO ', $faltaj->fk_empleado, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::text('nom_emp', 'NOMBRE ', $faltaj->Nom_Emp . ' ' . $faltaj->Ap_Pat . ' ' . $faltaj->Ap_Mat, [
                                'width' => 'col-md-3',
                                'readonly',
                            ]) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::text('departamento', 'Departamento ', $faltaj->Departamento, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                        @if (auth()->user()->hasRole('Administrador Sistema'))
                            <div class="col-md-2">
                                {!! BootForm::select('oficioentregado', 'Oficio entregado', ['SELECCIONE', 'SI' => 'SI', 'NO' => 'NO']) !!}
                            </div>
                        @endif
                        <br>
                    </div>
                    @if (auth()->user()->hasRole('Servicio Medico'))
                        <div class="row">
                            <div class="col-md-7">

                                {!! BootForm::file('fileincapacidad', 'Fortmato incapacidad. ' . $faltaj->formato_incapacidad, [
                                    'class' => 'form-control-file',
                                    'accept' => 'application/pdf',
                                ]) !!}
                                <br>
                                {!! BootForm::file('filest9', 'Probable enfermedad de trabajo | ST-9. ' . $faltaj->formato_st9, [
                                    'class' => 'form-control-file',
                                    'accept' => 'application/pdf',
                                ]) !!}
                                <br>
                                {!! BootForm::file('filest7', 'Probable accidente de trabajo | ST-7. ' . $faltaj->formato_st7, [
                                    'class' => 'form-control-file',
                                    'accept' => 'application/pdf',
                                ]) !!}
                                <br>
                                {!! BootForm::file('filest3', 'Incapacidad permanente o defuncion | ST-3. ' . $faltaj->formato_st3, [
                                    'class' => 'form-control-file',
                                    'accept' => 'application/pdf',
                                ]) !!}
                                <br>
                                {!! BootForm::file('filealta', 'Dictamen de alta por riesgo de trabajo | ST-2. ' . $faltaj->formato_alta, [
                                    'class' => 'form-control-file',
                                    'accept' => 'application/pdf',
                                ]) !!}
                            </div>
                        </div>
                    @endif
                    <br>
                    <div class="row" id='id_enviar'>
                        <div class="col center">
                            <button type="submit" name="solicitar" id='solicitar' value="Solicitar permisos"
                                class="btn btn-primary">Actualizar</button>
                            <a href="{!! route('home') !!}" class="btn btn-light">Cancelar</a>

                        </div>
                    </div>
                    <br>

                    {!! Form::close() !!}
                @empty
                @endforelse
            @endisset
        </div>
    </div>
@endsection

@section('scriptBFile')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.js"></script>
@endsection
