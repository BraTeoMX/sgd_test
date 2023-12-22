@extends('layouts.main')
@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Solicitudes de Vacaciones1</h3>
        </div>

        <div class="card-body">

            {!! BootForm::open(['id' => 'form', 'method' => 'GET']) !!}
            <!-- <div class="form-group">
                <div class="col-6 input-group">
                    <label class="text-dark" style="line-height: 2;"> No. de Empleado :&nbsp;</label>
                    <input type="search" class="form-control col-8" id="no_empleado" name="no_empleado">
                    <span class="col-2">
                        {!! Form::submit('Buscar', ['class' => 'btn btn-light']) !!}
                   </span>
                </div>
            </div> -->

            {!! BootForm::close() !!}

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table toggle-circle">

                            <thead>

                                <th></th>
                                <th>Estatus</th>

                                <th>Folio</th>
                                <th>Fecha de solicitud</th>
                                <th>Fecha autorización</th>
                                <th>Inicio Vacaciones</th>
                                <th>Fin Vacaciones</th>
                                <th>No. Empleado</th>
                                <th>Modulo</th>
                                <th>Nombre</th>
                                <th>Puesto</th>
                                <th>Departamento</th>


                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($vacaciones as $vac)
                                    @if ($vac->status == 'APLICADO')
                                        @php
                                            $i++;
                                        @endphp
                                    @endif
                                @endforeach
                                <input type="hidden" value="{{ $repetido }}" id="existe">
                                @foreach ($vacaciones as $vacacion)
                                    @if (auth()->user()->hasRole('Jefe Administrativo') ||
                                            auth()->user()->hasRole('Vicepresidente') ||
                                            auth()->user()->hasRole('Coordinador/Analista') ||
                                            auth()->user()->hasRole('Seguridad e Higiene')||
                                            auth()->user()->hasRole('Gerente Produccion'))
                                        @if ($vacacion->Departamento != 'PRODUCCION')
                                            <tr>
                                                <td></td>


                                                @if ($vacacion->status == 'PENDIENTE')
                                                    @foreach ($parametros as $parametro)
                                                        @if ($parametro->clave == 'aus_vac')
                                                            @php
                                                                $valor_ausentismo = ceil($parametro->valor);
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @if ($repetido == 0)
                                                        <td><a class="btn btn-info"
                                                                href="{!! route('vacaciones.liberarPermiso', $vacacion->folio_vac) !!} ">Liberar</a></td>

                                                        <td class="float-center">
                                                            {!! Form::model($vacaciones, ['method' => 'update', 'route' => ['vacaciones.update', $vacacion->folio_vac]]) !!}
                                                            <a class="text-danger denegar" style="cursor: pointer"
                                                                onclick="">
                                                                <i class="btn btn-danger ">Denegar</i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </td>
                                                    @endif
                                                @else
                                                    <td>{{ $vacacion->status }}</td>
                                                @endif
                                                <td>{{ $vacacion->folio_vac }}</td>
                                                <td>{{ $vacacion->fecha_solicitud }}</td>
                                                <td>{{ $vacacion->fecha_aprobacion }}</td>
                                                <td>{{ $vacacion->fech_ini_vac }}</td>
                                                <td>{{ $vacacion->fech_fin_vac }}</td>
                                                <td>{{ $vacacion->fk_no_empleado }}</td>
                                                <td></td>
                                                <td>{{ $vacacion->Nom_Emp . ' ' . $vacacion->Ap_Pat . ' ' . $vacacion->Ap_Mat }}
                                                </td>
                                                <td>{{ $vacacion->Puesto }}</td>
                                                <td>{{ $vacacion->Departamento }}</td>

                                                @if (auth()->user()->hasRole('Administrador') &&
                                                        $vacacion->status != 'CANCELADO' &&
                                                        $vacacion->status != 'PENDIENTE')
                                                    <td class="float-center">
                                                        {!! Form::model($vacaciones, ['method' => 'update', 'route' => ['vacaciones.update', $vacacion->folio_vac]]) !!}
                                                        <a class="text-danger cancelar" style="cursor: pointer"
                                                            onclick="">
                                                            <i class="btn btn-danger ">Cancelar</i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </td>
                                                @endif
                                            </tr>
                                        @else
                                            <tr>
                                                <td></td>


                                                @if ($vacacion->status == 'PENDIENTE')
                                                    @foreach ($parametros as $parametro)
                                                        @if ($parametro->clave == 'aus_vac')
                                                            @php
                                                                $valor_ausentismo = ceil($parametro->valor);
                                                            @endphp
                                                        @endif
                                                    @endforeach


                                                    <td><a class="btn btn-info" href="{!! route('vacaciones.liberarPermiso', $vacacion->folio_vac) !!} ">Liberar</a>
                                                    </td>
                                                    <td class="float-center">
                                                        {!! Form::model($vacaciones, ['method' => 'update', 'route' => ['vacaciones.update', $vacacion->folio_vac]]) !!}
                                                        <a class="text-danger eliminar" style="cursor: pointer"
                                                            onclick="">
                                                            <i class="btn btn-danger ">Denegar</i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </td>
                                                @else
                                                    <td>{{ $vacacion->status }}</td>
                                                @endif
                                                <td>{{ $vacacion->folio_vac }}</td>
                                                <td>{{ $vacacion->fecha_solicitud }}</td>
                                                <td>{{ $vacacion->fecha_aprobacion }}</td>
                                                <td>{{ $vacacion->fech_ini_vac }}</td>
                                                <td>{{ $vacacion->fech_fin_vac }}</td>
                                                <td>{{ $vacacion->fk_no_empleado }}</td>
                                                <td>{{ $vacacion->Modulo }}</td>
                                                <td>{{ $vacacion->Nom_Emp . ' ' . $vacacion->Ap_Pat . ' ' . $vacacion->Ap_Mat }}
                                                </td>
                                                <td>{{ $vacacion->Puesto }}</td>
                                                <td>{{ $vacacion->Departamento }}</td>
                                            </tr>
                                        @endif
                                    @else
                                        @if ($vacacion->Departamento == 'PRODUCCION')
                                            <tr>
                                                <td></td>

                                                @if ($vacacion->status == 'PENDIENTE')
                                                    @foreach ($parametros as $parametro)
                                                        @if ($parametro->clave == 'aus_vac')
                                                            @php
                                                                $valor_ausentismo = ceil($parametro->valor);
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    @if ($repetido == 0)
                                                        <td><a class="btn btn-info"
                                                                href="{!! route('vacaciones.liberarPermiso', $vacacion->folio_vac) !!} ">Liberar</a></td>
                                                        <td class="float-center">
                                                            {!! Form::model($vacaciones, ['method' => 'update', 'route' => ['vacaciones.update', $vacacion->folio_vac]]) !!}
                                                            <a class="text-danger eliminar" style="cursor: pointer"
                                                                onclick="">
                                                                <i class="btn btn-danger ">Denegar</i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </td>
                                                    @endif
                                                @else
                                                    <td>{{ $vacacion->status }}</td>
                                                @endif
                                                <td>{{ $vacacion->folio_vac }}</td>
                                                <td>{{ $vacacion->fecha_solicitud }}</td>
                                                <td>{{ $vacacion->fecha_aprobacion }}</td>
                                                <td>{{ $vacacion->fech_ini_vac }}</td>
                                                <td>{{ $vacacion->fech_fin_vac }}</td>
                                                <td>{{ $vacacion->fk_no_empleado }}</td>
                                                <td>{{ $vacacion->Modulo }}</td>
                                                <td>{{ $vacacion->Nom_Emp . ' ' . $vacacion->Ap_Pat . ' ' . $vacacion->Ap_Mat }}
                                                </td>
                                                <td>{{ $vacacion->Puesto }}</td>
                                                <td>{{ $vacacion->Departamento }}</td>

                                            </tr>
                                        @endif
                                    @endif
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>

@section('scriptBFile')
    <script>
        $(document).ready(function() {
            if ($('#existe').val() == 1) {
                Swal.fire({
                    title: '',
                    text: "Estimado colaborador, tu solicitud fue denegada por porcentaje de ausentismo.",
                    imageUrl: 'img/logo.png',
                    imageWidth: 400,
                    imageHeight: 200,
                    imageAlt: 'Custom image',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!'
                }).then((result) => {
                    //  $(this).closest('form').submit();
                    /* if (result.isConfirmed) {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                       }*/
                })
            };


            $('.eliminar').on('click', function(event) {


                Swal.fire({
                    title: 'Estimado colaborador, esta seguro de denegar la solicitud?',
                    text: "",
                    //icon: 'warning',
                    imageUrl: 'img/logo.png',
                    imageWidth: 400,
                    imageHeight: 200,
                    imageAlt: 'Custom image',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'La solicitud ha sido Denegada satisfactoriamente!'
                        )
                        $(this).closest('form').submit();
                    }
                })
                /* event.preventDefault();
                    var respuesta = confirm('¿Desea cancelar la solicitud?');
                    if (respuesta) {

                        $(this).closest('form').submit();
                    } else {
                        return false;
                    }*/
            });

            $('.cancelar').on('click', function(event) {


                Swal.fire({
                    title: 'Estimado colaborador, esta seguro de cancelar la solicitud?',
                    text: "",
                    //icon: 'warning',
                    imageUrl: 'img/logo.png',
                    imageWidth: 400,
                    imageHeight: 200,
                    imageAlt: 'Custom image',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'La solicitud ha sido Cancelada satisfactoriamente!'

                        )
                        $(this).closest('form').submit();
                    }
                })
                /* event.preventDefault();
                   var respuesta = confirm('¿Desea cancelar la solicitud?');
                   if (respuesta) {

                       $(this).closest('form').submit();
                   } else {
                       return false;
                   }*/
            });

            $('.denegar').on('click', function(event) {
                //  alert('Solicitud Denegada por Porcentaje de ausentismo en el modulo');
                Swal.fire({
                    title: '',
                    text: "Estimado colaborador, tu solicitud ha sido denegada por porcentaje de ausentismo en el módulo.",
                    imageUrl: 'img/logo.png',
                    imageWidth: 400,
                    imageHeight: 200,
                    imageAlt: 'Custom image',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!'
                }).then((result) => {
                    //  $(this).closest('form').submit();
                    /* if (result.isConfirmed) {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                       }*/
                })

                // $(this).closest('form').submit();
                //  window.location.href = 'vacaciones.update';

            });
        });
    </script>
@endsection
