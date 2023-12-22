@extends('layouts.main')
@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('materialfront/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('materialfront/assets/vendor/datatables.net.extensions/fixedColumns.dataTables.min.css') }}"
        rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-md-8 col-lg-8 text-rigth">
                        <h3>Consulta Incapacidades</h3>
                        {!! BootForm::open(['id' => 'form', 'method' => 'GET', 'route' => 'busquedaincapacidades']) !!}
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                {!! BootForm::number('no_empleado', 'No Empleado : ', null, ['width' => 'col-lg-3 col-md-3']) !!}
                            </div>
                            <div class="col-lg-4 col-md-4">
                                {!! BootForm::date('inicio_fecha', 'Fecha inicial : ', old('inicio_fecha'), ['width' => 'col-lg-3 col-md-3']) !!}
                            </div>
                            <div class="col-lg-4 col-md-4">
                                {!! BootForm::date('fin_fecha', 'Fecha final : ', old('fin_fecha'), ['width' => 'col-lg-3 col-md-3']) !!}
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
                            data-hs-datatables-options='{"order": [],
                                    "search": "#datatableWithSearchInput",
                                    "isResponsive": false,
                                    "isShowPaging": false,
                                    "paging": false}'>
                            <thead class="thead-light">
                                <tr>
                                    <th>Status</th>
                                    <th>Folio</th>
                                    <th>Fecha Inicio</th>
                                    <th>Dias</th>
                                    <th>Fecha Fin</th>
                                    <th>No. de empleado</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Ramo</th>
                                    <th>Riesgo</th>
                                    @if (auth()->user()->hasRole('Servicio Medico'))
                                        <th>Comentario</th>
                                        <th>Formato Incapacidad</th>
                                        <th>Enfermedad de trabajo ST-9</th>
                                        <th>Accidente de trabajo | ST-7</th>
                                        <th>Incapacidad permanente o defuncion | ST-3</th>
                                        <th>Formato alta | ST-2</th>
                                    @endif
                                    <th>Fecha registro</th>
                                    <th>Oficio entregado</th>
                                    <th>Departamento</th>
                                    <th>Editar datos</th>
                                    @if (auth()->user()->hasRole('Servicio Medico'))
                                        <th>Cancelar incapacidad</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @isset($reportes)
                                    @foreach ($reportes as $reporte)
                                        <tr>
                                            <td>
                                                {{ $reporte->status }}
                                            </td>
                                            <td>
                                                {{ $reporte->folio_incapacidad }}
                                            </td>
                                            <td>
                                                {{ $reporte->fecha_inicio }}
                                            </td>
                                            <td>
                                                {{ $reporte->dias }}
                                            </td>
                                            <td>
                                                {{ $reporte->fecha_fin }}
                                            </td>
                                            <td>
                                                {{ $reporte->fk_empleado }}
                                            </td>
                                            <td>
                                                {{ $reporte->Nom_Emp . ' ' . $reporte->Ap_Pat . ' ' . $reporte->Ap_Mat }}
                                            </td>

                                            @if ($reporte->tipo_incapacidad === '01')
                                                <td>
                                                    INICIAL
                                                </td>
                                            @elseif ($reporte->tipo_incapacidad === '02')
                                                <td>
                                                    SUBSECUENTE
                                                </td>
                                            @elseif ($reporte->tipo_incapacidad === '03')
                                                <td>
                                                    RECAIDA
                                                </td>
                                            @else
                                                <td>
                                                    PROBABLE RIESGO
                                                </td>
                                            @endif
                                            @if ($reporte->ramo_seguro === '01')
                                                <td>
                                                    ENFERMEDAD GENERAL
                                                </td>
                                            @else
                                                <td>
                                                    MATERNIDAD
                                                </td>
                                            @endif
                                            @if ($reporte->riesgo === '01')
                                                <td>
                                                    SI
                                                </td>
                                            @else
                                                <td>
                                                    NO
                                                </td>
                                            @endif
                                            @if (auth()->user()->hasRole('Servicio Medico'))
                                                <td>
                                                    {{ Crypt::decrypt($reporte->comentario) }}
                                                </td>
                                                <td>
                                                    {{ $reporte->formato_incapacidad }}
                                                    <br>
                                                    <!--<a href="Documentos/Sem 08_Victor Manuel Cruz Mendoza.pdf" target="_blank">Ver archivo</a>-->
                                                    <a href="{{ str_replace('E:\xampp1\htdocs\intimark_sgd\public\Documentos', 'Documentos', $reporte->ruta_incapacidad) }}"
                                                        target="_blank">Ver documento</a>
                                                </td>
                                                <td>
                                                    {{ $reporte->formato_st9 }}
                                                    <br>
                                                    <!--<a href="Documentos/Sem 08_Victor Manuel Cruz Mendoza.pdf" target="_blank">Ver archivo</a>-->
                                                    <a href="{{ str_replace('E:\xampp1\htdocs\intimark_sgd\public\Documentos', 'Documentos', $reporte->ruta_st9) }}"
                                                        target="_blank">Ver documento</a>
                                                </td>
                                                <td>
                                                    {{ $reporte->formato_st7 }}
                                                    <br>
                                                    <!--<a href="Documentos/Sem 08_Victor Manuel Cruz Mendoza.pdf" target="_blank">Ver archivo</a>-->
                                                    <a href="{{ str_replace('E:\xampp1\htdocs\intimark_sgd\public\Documentos', 'Documentos', $reporte->ruta_st7) }}"
                                                        target="_blank">Ver documento</a>
                                                </td>
                                                <td>
                                                    {{ $reporte->formato_st3 }}
                                                    <br>
                                                    <!--<a href="Documentos/Sem 08_Victor Manuel Cruz Mendoza.pdf" target="_blank">Ver archivo</a>-->
                                                    <a href="{{ str_replace('E:\xampp1\htdocs\intimark_sgd\public\Documentos', 'Documentos', $reporte->ruta_st3) }}"
                                                        target="_blank">Ver documento</a>
                                                </td>
                                                <td>
                                                    {{ $reporte->formato_alta }}
                                                    <br>
                                                    <!--<a href="Documentos/Sem 08_Victor Manuel Cruz Mendoza.pdf" target="_blank">Ver archivo</a>-->
                                                    <a href="{{ str_replace('E:\xampp1\htdocs\intimark_sgd\public\Documentos', 'Documentos', $reporte->ruta_alta) }}"
                                                        target="_blank">Ver documento</a>
                                                </td>
                                            @endif
                                            <td>
                                                {{ $reporte->fecha_registro }}
                                            </td>
                                            <td>
                                                {{ $reporte->oficioentregado }}
                                            </td>
                                            <td>
                                                {{ $reporte->Departamento }}
                                            </td>
                                            @if (auth()->user()->hasRole('Servicio Medico') ||
                                                    auth()->user()->hasRole('Administrador Sistema'))
                                                <td class="float-center">
                                                    <a class="btn btn-info float-right"
                                                        href="{{ route('incapacidades.show', $reporte->id) }}"
                                                        method="PUT">Modificar</a>
                                                </td>
                                            @endif
                                            @if (auth()->user()->hasRole('Servicio Medico'))
                                                <td class="float-center">
                                                    <a class="btn btn-danger float-right"
                                                        href="{{ route('cancelarincapacidad', $reporte->id) }}">Cancelar</a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
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
