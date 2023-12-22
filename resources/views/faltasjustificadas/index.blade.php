@extends('layouts.main')

@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Faltas Justificadas</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'faltasjustificadas.create', 'method' => 'get', 'files' => true]) !!}
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    {!! BootForm::text('no_empleado', 'No. de Empleado ', null, ['id' => 'no_empleado']) !!}
                </div>
            </div>
            <div class="row" style="display" id='id_buscar'>
                <div class="col center">
                    <button type="submit" name="solicitar" id='solicitar' value="Solicitar permisos"
                        class="btn btn-primary">Buscar empleado</button>
                    <a href="{!! route('home') !!}" class="btn btn-light">Cancelar</a>

                </div>
            </div>
            {!! form::close() !!}
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header">
            <h3>Datos Del Empleado</h3>
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
            @isset($empleado)
                @php
                    $hoy = date('Y-m-d');
                    $ayer = date('Y-m-d', strtotime($hoy . '- 1 days'));
                @endphp
                @forelse ($empleado as $emp)
                    {!! Form::open(['route' => 'faltasjustificadas.store', 'method' => 'post', 'files' => true]) !!}
                    {!! BootForm::hidden('no_aux', auth()->user()->no_empleado) !!}
                    <div class="row">
                        <div class="col-md-3">
                            {!! BootForm::text('no_emp', 'No. EMPLEADO ', $emp->No_Empleado, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::text('nom_empleado', 'Nombre ', $emp->Nom_Emp, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::text('ap_pat', 'Apellido Paterno ', $emp->Ap_Pat, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::text('ap_mat', 'Apellido Materno', $emp->Ap_Mat, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            {!! BootForm::text('departamento', 'Departamento', $emp->Departamento, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::date('fecha_falta', 'Fecha inicio', null, ['width' => 'col-md-3']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::date('fecha_fin', 'Fecha fin', null, ['width' => 'col-md-3']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::select('motivo','Motivo',['SELECCIONE UN MOTIVO'] + $motivos->toArray(),) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            {!! BootForm::textarea('comentario', 'Comentarios', '', ['rows' => '2', 'style' => 'rezise:true']) !!}
                        </div>
                        <div class="col-lg-6 col-md-6">
                            {!! Form::label('file', 'Seleccione un archivo') !!}
                            {!! Form::file('file', ['class' => 'form-control-file']) !!}
                        </div>
                    </div>
                    <div class="row" style="display: none" id='id_enviar'>
                        <div class="col center">
                            <button type="submit" name="solicitar" id='solicitar' value="Solicitar permisos"
                                class="btn btn-primary">Enviar</button>
                            <a href="{!! route('home') !!}" class="btn btn-light">Cancelar</a>

                        </div>
                    </div>
                    {!! Form::close() !!}
                @empty
                    <h3>No hay datos del empleado</h3>
                @endforelse
            @endisset
        </div>
    </div>
@endsection

@section('scriptBFile')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function() {
            var fechaActual = new Date().toISOString().split("T")[0];
            $('#fecha_falta').attr('max', fechaActual);
            $('#fecha_fin').attr('max', fechaActual);

            document.getElementById("no_empleado").focus();

            function verificarCampos() {
                var fechaFalta = $('#fecha_falta').val();
                var fechaFin = $('#fecha_fin').val();
                var motivo = $('#motivo').val();

                if (fechaFalta && fechaFin && motivo !== 'SELECCIONE UN MOTIVO') {
                    $('#id_enviar').show(); // Mostrar el contenedor del botón
                } else {
                    $('#id_enviar').hide(); // Ocultar el contenedor del botón
                }
            }

            $('#fecha_falta')[0].addEventListener('input', verificarCampos);
            $('#fecha_fin')[0].addEventListener('input', verificarCampos);
            $('#motivo')[0].addEventListener('change', verificarCampos);

            $('#fecha_falta').change(function() {
                $.ajax({
                    url: '/faltasjustificadas.fecha_ini',
                    method: 'POST',
                    data: {
                        no_emp: $('#no_emp').val(),
                        fecha_ini: $('#fecha_falta').val(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                }).done(function(res) {
                    if (res == 0) {
                        Swal.fire({
                            title: '',
                            text: "Estimado colaborador, no existe alguna falta en la fecha seleccionada.",
                            imageUrl: '/img/logo.png',
                            imageWidth: 300,
                            imageHeight: 150,
                            imageAlt: 'Custom image',
                            position: 'top',
                            //icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar!'
                        }).then((result) => {
                            $('#fecha_falta').val('');
                        })
                    };
                    //alert(res);
                });
                //alert('hola')
                //alert($('#no_emp').val())
                //alert($('#fecha_falta').val())
            });
            $('#fecha_fin').change(function() {
                $.ajax({
                    url: '/faltasjustificadas.fecha_fin',
                    method: 'POST',
                    data: {
                        no_emp: $('#no_emp').val(),
                        fecha_fin: $('#fecha_fin').val(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                }).done(function(res) {
                    if (res == 0) {
                        Swal.fire({
                            title: '',
                            text: "Estimado colaborador, no existe alguna falta en la fecha seleccionada.",
                            imageUrl: 'img/logo.png',
                            imageWidth: 300,
                            imageHeight: 150,
                            imageAlt: 'Custom image',
                            position: 'top',
                            //icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar!'
                        }).then((result) => {
                            $('#fecha_fin').val('');
                        })
                    };
                });
            });


        });
    </script>
@endsection
