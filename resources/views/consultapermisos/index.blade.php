@extends('layouts.main')
@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('materialfront/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('materialfront/assets/vendor/datatables.net.extensions/fixedColumns.dataTables.min.css') }}"
        rel="stylesheet">
@endsection
@section('content')
    <style>
        .pagination {
            float: right;
            margin-top: 10px;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-md-8 col-lg-8 text-rigth">
                                <h3>Consulta Permisos</h3>
                                {!! BootForm::open(['id' => 'form', 'method' => 'GET']) !!}
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {!! BootForm::date('inicio_fecha', 'Fecha inicial : ', $inicio, ['width' => 'col-lg-3 col-md-3']) !!}
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        {!! BootForm::date('fin_fecha', 'Fecha final : ', $fin, ['width' => 'col-lg-3 col-md-3']) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        {!! Form::submit('Buscar', ['class' => 'btn btn-primary']) !!}
                                    </div>
                                </div>
                                {!! BootForm::close() !!}
                            </div>
                            <!-- Unfold -->
                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker btn btn-white dropdown-toggle" href="javascript:;"
                                    data-hs-unfold-options='{
                            "target": "#dropdownHover",
                            "type": "css-animation",
                            "event": "hover" }'>
                                    <i class="tio-download-to mr-1"></i>Exportar
                                </a>
                                <div id="dropdownHover" class="hs-unfold-content dropdown-unfold dropdown-menu">
                                    <a id="export-print" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2" src="{!! asset('materialfront/assets/svg/illustrations/print.svg') !!}"
                                            alt="Imprimir">
                                        Imprimir
                                    </a>
                                    <a id="export-copy" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2" src="{!! asset('materialfront/assets/svg/illustrations/copy.svg') !!}"
                                            alt="Image Description">
                                        Copiar
                                    </a>
                                    <a id="export-excel" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2" src="{!! asset('materialfront/assets/svg/brands/excel.svg') !!}"
                                            alt="Excel">
                                        Excel
                                    </a>
                                    <a id="export-csv" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2" src="{!! asset('materialfront/assets/svg/components/placeholder-csv-format.svg') !!}"
                                            alt="CSV">
                                        .CSV
                                    </a>
                                    <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2" src="{!! asset('materialfront/assets/svg/brands/pdf.svg') !!}"
                                            alt="PDF">
                                        PDF
                                    </a>
                                </div>
                            </div>
                            <!-- End Unfold -->
                            <div class="col-auto">
                                <!-- Filter -->
                                <form>
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableWithSearchInput" type="search" class="form-control"
                                            placeholder="Buscar" aria-label="Buscar">
                                    </div>
                                    <!-- End Search -->
                                </form>
                                <!-- End Filter -->
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Table -->
                            <div id="datatableWithSearchInput" class="table-responsive datatable-custom">
                                <table id="exportOptionsDatatables"
                                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                    data-hs-datatables-options='{
                    "order": [],
                    "search": "#datatableWithSearchInput",
                "isResponsive": false,
                "isShowPaging": false,
                    "paging": false
                }'>
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Estatus</th>
                                            <th>No. Empleado</th>
                                            <th>Nombre</th>
                                            <th>Inicio Permiso</th>
                                            <th>Fin Permiso</th>
                                            <th>Hora Inicial</th>
                                            <th>Hora Final</th>
                                            <th>Tiempo Comida</th>
                                            <th>Tiempo Minutos</th>
                                            <th>Motivo</th>
                                            <th>Modo</th>
                                            <th>Folio</th>
                                            <th>Fecha Solicitud</th>
                                            <th>Planta</th>
                                            <th>Turno</th>
                                            <th>Modulo</th>
                                            <th>Puesto</th>
                                            <th>Departamento</th>
                                            <th>Jefe Inmediato</th>
                                            <th>Autorizó</th>
                                            <th>Excepcion</th>

                                            @if (auth()->user()->hasRole('Administrador Sistema'))
                                                <th></th>
                                            @endif
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach ($permisos as $permiso)
                                            <tr>
                                                <td>{{ $permiso->status }}</td>
                                                <td>{{ $permiso->fk_no_empleado }}</td>
                                                <td>{{ $permiso->Nom_Emp . ' ' . $permiso->Ap_Pat . ' ' . $permiso->Ap_Mat }}</td>

                                                <td>{{ $permiso->fech_ini_per }}</td>
                                                <td>{{ $permiso->fech_fin_per }}</td>
                                                @if ($permiso->firmado == 1)
                                                    <td>{{ $permiso->fech_ini_hor }}</td>
                                                    <td>{{ $permiso->fech_fin_hor }}</td>
                                                @else
                                                    <td>{{ $permiso->fech_ini_hor }}</td>
                                                    <td>{{ $permiso->fech_fin_hor }}</td>
                                                @endif
                                                @if ($permiso->hora_comida == 1)
                                                    <td>{{ 'MEDIA HORA' }}</td>
                                                @else
                                                    @if ($permiso->hora_comida == 2)
                                                        <td>{{ 'UNA HORA' }}</td>
                                                    @else
                                                        @if ($permiso->hora_comida == 3)
                                                            <td>{{ 'NO APLICA' }}</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                @endif
                                                @php
                                                    $hora1 = new DateTime($permiso->fech_fin_hor);
                                                    $hora2 = new DateTime($permiso->fech_ini_hor);
                                                    $diferencia = $hora1->diff($hora2);
                                                    $minutos = $diferencia->h * 60;
                                                    $minutos += $diferencia->i;
                                                @endphp
                                                @if ($permiso->hora_comida == 1)
                                                    @php
                                                        $minutos = $minutos - 30;
                                                    @endphp
                                                @else
                                                    @if ($permiso->hora_comida == 2)
                                                        @php
                                                            $minutos = $minutos - 60;
                                                        @endphp
                                                    @endif
                                                @endif

                                                <td>{{ $minutos }}</td>
                                                <td>{{ $permiso->permiso }}</td>
                                                @if ($permiso->modo_per == 1)
                                                    <td>{{ 'ENTRADA' }}</td>
                                                @else
                                                    @if ($permiso->modo_per == 2)
                                                        <td>{{ 'SALIDA' }}</td>
                                                    @else
                                                        @if ($permiso->modo_per == 3)
                                                            <td>{{ 'ENTRADA/SALIDA' }}</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                @endif
                                                <td>{{ $permiso->folio_per }}</td>
                                                @if($permiso->status == 'CANCELADO')
                                                <td>{{$permiso->updated_at}}</td>
                                                @else
                                                <td>{{$permiso->fecha_solicitud}}</td>
                                                @endif
                                                <td>{{ $permiso->Id_Planta }}</td>
                                                <td>{{ $permiso->Id_Turno }}</td>
                                                <td>{{ $permiso->Modulo }}</td>
                                                <td>{{ $permiso->Puesto }}</td>
                                                <td>{{ $permiso->Departamento }}</td>
                                                @foreach ($jefe as $autoriza)
                                                    @if ($permiso->jefe_directo == 0)
                                                        <td></td>
                                                    @else
                                                        @if ($autoriza->id_puesto == $permiso->jefe_directo)
                                                            @if($autoriza->Puesto == 'ANALISTA PRODUCCION')
                                                                <td>TEAM LEADER</td>
                                                            @else
                                                                @if($autoriza->Puesto == 'ANALISTA ORDENES MANUFACT II')
                                                                    <td>GERENTE MANUFACTURA</td>
                                                                @else
                                                                    @if ($autoriza->Puesto == 'ANALISTA CALIDAD')
                                                                        <td> GERENTE CONTROL DE CALIDAD</td>
                                                                    @else
                                                                        @if ($autoriza->Puesto == 'ANALISTA CORTE')
                                                                        <td>GERENTE CORTE</td>
                                                                        @else
                                                                            <td>{{ $autoriza->Puesto }}</td>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                                <td>{{ $permiso->autorizado_por }}</td>
                                                @if ($permiso->excepcion == 1)
                                                    <td>{{ 'SI' }}</td>
                                                @else
                                                    <td>{{ 'NO' }}</td>
                                                @endif
                                                @if (auth()->user()->hasRole('Administrador Sistema'))
                                                    <td class="float-center">
                                                        <a class="btn btn-light float-right"
                                                            href="{{ route('consultapermisos.ticket', $permiso->folio_per) }}">Formato</a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>

                            </div>
                            <!-- End Table -->
                        </div>
                    </div>
                </div>
            </div>
        @endsection
        @section('scriptBFile')
            <script src="{!! asset('materialfront/assets/vendor/datatables/media/js/jquery.dataTables.min.js') !!}"></script>
            <script src="{!! asset('materialfront/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js') !!}"></script>
            <script src="{!! asset('materialfront/assets/vendor/datatables.net-buttons/js/buttons.flash.min.js') !!}"></script>
            <script src="{!! asset('materialfront/assets/vendor/jszip/dist/jszip.min.js') !!}"></script>
            <script src="{!! asset('materialfront/assets/vendor/pdfmake/build/pdfmake.min.js') !!}"></script>
            <script src="{!! asset('materialfront/assets/vendor/pdfmake/build/vfs_fonts.js') !!}"></script>
            <script src="{!! asset('materialfront/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js') !!}"></script>
            <script src="{!! asset('materialfront/assets/vendor/datatables.net-buttons/js/buttons.print.min.js') !!}"></script>
            <script src="{!! asset('materialfront/assets/vendor/datatables.net-buttons/js/buttons.colVis.min.js') !!}"></script>
            <script src="{!! asset('materialfront/assets/vendor/datatables.net.extensions/dataTables.fixedColumns.min.js') !!}"></script>
            <script>
                $(document).ready(function() {
                    var datatable = $.HSCore.components.HSDatatables.init($('#datatableWithSearch'));
                    // initialization of sortable
                    $('.js-sortable').each(function() {
                        var sortable = $.HSCore.components.HSSortable.init($(this));
                    });
                    // initialization of datatables
                    var datatable = $.HSCore.components.HSDatatables.init($('#exportOptionsDatatables'), {
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'copy',
                                className: 'd-none'
                            },
                            {
                                extend: 'excel',
                                className: 'd-none'
                            },
                            {
                                extend: 'csv',
                                className: 'd-none'
                            },
                            {
                                extend: 'pdf',
                                className: 'd-none'
                            },
                            {
                                extend: 'print',
                                className: 'd-none'
                            },
                        ]
                    });

                    $('#export-copy').click(() => {
                        datatable.button('.buttons-copy').trigger()
                    });

                    $('#export-excel').click(() => {
                        datatable.button('.buttons-excel').trigger()
                    });

                    $('#export-csv').click(() => {
                        datatable.button('.buttons-csv').trigger()
                    });

                    $('#export-pdf').click(() => {
                        datatable.button('.buttons-pdf').trigger()
                    });

                    $('#export-print').click(() => {
                        datatable.button('.buttons-print').trigger()
                    });
                });
            </script>
        @endsection
