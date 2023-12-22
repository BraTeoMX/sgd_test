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
            @isset($faltajustificada)
                @forelse ($faltajustificada as $faltaj)
                    {!! Form::open(['route' => 'faltasjustificadas.nuevoarchivo', 'method' => 'post', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-md-4">
                            {!! BootForm::text('folio', 'Folio ', $faltaj->folio_falta, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                        <div class="col-md-4">
                            {!! BootForm::text('no_emp', 'No. EMPLEADO ', $faltaj->fk_no_empleado, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                        <div class="col-md-4">
                            {!! BootForm::text('nom_emp', 'NOMBRE ', $faltaj->Nom_Emp.' '.$faltaj->Ap_Pat.' '.$faltaj->Ap_Mat, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            {!! BootForm::text('no_emp', 'Fecha ', $faltaj->fecha_inicio_justificar, ['width' => 'col-md-3', 'readonly']) !!}
                        </div>
                        <div class="col-md-4">
                            {!! BootForm::text('no_emp', 'Archivo ', $faltaj->nombre_archivo, ['width' => 'col-md-3', '']) !!}
                        </div>
                        <div class="col-lg-4 col-md-4">
                            {!! Form::label('file', 'Seleccione un archivo') !!}
                            {!! Form::file('file', ['class' => 'form-control-file']) !!}
                        </div>
                    </div>
                    <div class="row" id='id_enviar'>
                        <div class="col center">
                            <button type="submit" name="solicitar" id='solicitar' value="Solicitar permisos"
                                class="btn btn-primary">Actualizar</button>
                            <a href="{!! route('home') !!}" class="btn btn-light">Cancelar</a>

                        </div>
                    </div>

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

    <script>
        $(document).ready(function() {


            document.getElementById("no_empleado").focus();
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
