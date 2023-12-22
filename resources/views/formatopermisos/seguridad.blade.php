@extends('layouts.main')

@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">
@endsection


@section('content')

    <div class="card">
        <div class="card-header">
            <h3>Seguridad - Permisos</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'formatopermisos.permisoempleado', 'method' => 'POST', 'files' => true]) !!}

            <div class="row">
                <div class="col-lg-3 col-md-3">
                    {!! BootForm::text('no_empleado', 'No. de Empleado ', null, ['id' => 'no_empleado']) !!}

                </div>

            </div>
            <br>
            <div class="row" style="display" id='id_enviar'>
                <div class="col center">
                    <button type="submit" name="solicitar" id='solicitar' value="Solicitar permisos"
                        class="btn btn-primary">Buscar empleado</button>
                    <a href="{!! route('home') !!}" class="btn btn-light">Cancelar</a>

                </div>
            </div>

            {!! form::close() !!}
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table" data-page-size="50">
                    @isset($permiso)
                        <thead style="">
                            <tr>
                                <th data-sortable="true">Folio</th>
                                <th data-sortable="true">No. Empleado</th>
                                <th data-sortable="true">Nombre Empleado</th>
                                <th data-sortable="true">Fecha Permiso</th>

                                <th data-sortable="true">Hora Entrada</th>

                                <th data-sortable="true">Hora Salida</th>

                                <th data-sortable="true"></th>
                                <th data-sortable="true"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($permiso as $per)
                                @if ($per->status == 'APLICADO' )
                                    <tr>
                                        <td>
                                            {{ $per->folio_per }}
                                            {!! BootForm::hidden('status', $per->status) !!}
                                        </td>
                                        <td>
                                            {{ $per->fk_no_empleado }}
                                        </td>
                                        <td>
                                            {{ $nombre }}
                                        </td>

                                        <td>
                                            {{ $per->fech_ini_per }}
                                        </td>

                                        @if ($per->modo_per == 1 || $per->modo_per == 3)
                                            <td>
                                                {{ $per->fech_ini_hor }}
                                            </td>
                                        @else
                                            <td>

                                            </td>
                                        @endif
                                        @if ($per->modo_per == 2 || $per->modo_per == 3)
                                            <td>
                                                {{ $per->fech_fin_hor }}
                                            </td>
                                        @else
                                            <td>

                                            </td>
                                        @endif
                                        @if (($per->modo_per == 1 || $per->modo_per == 3) /*&& $per->entrada_permiso==0*/)
                                            <td>
                                                <a class="btn btn-info" id="ini_hor" nam="ini_hor"
                                                    href="{{ route('formatopermisos.revisarpermiso', $per->folio_per) }}">
                                                    Entrada
                                                </a>
                                            </td>
                                        @endif
                                        @if (($per->modo_per == 2 || $per->modo_per == 3) /*&& $per->salida_permiso==0*/)
                                            <td>
                                                <a class="btn btn-danger " id="ini_hor" nam="ini_hor"
                                                    href="{{ route('formatopermisos.revisarEntrada', $per->folio_per) }}">
                                                    Salida
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @else
                                    @if ($per->status != 'APLICADO' )
                                    <tr>
                                        <td colspan="7" style="color: green; background: black;">El permiso no se encuentra
                                            Liberado, favor de Verificar</td>
                                    </tr>
                                    @endif
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" style="color: red; background: black;">No existe un permiso Registrado,
                                        favor de Verificar</td>
                                </tr>
                            @endforelse

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                    <div>
                                        <ul class="pagination"></ul>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    @endisset
                </table>

            </div>
        </div>

    </div>

@endsection

@section('scriptBFile')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>

    <script>
        document.getElementById("no_empleado").focus();

        /* $( "#ini_hor" ).click(function() {
             Swal.fire('Registrado')
         });*/
    </script>
@endsection
