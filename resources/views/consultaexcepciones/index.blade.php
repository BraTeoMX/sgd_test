@extends('layouts.main')
@section('styleBFile')
<!-- Color Box -->
<link href="{{ asset('materialfront/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('materialfront/assets/vendor/datatables.net.extensions/fixedColumns.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="row">   
    <div class="col-12">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"> 
                <div class="col-md-8 col-lg-8 text-rigth">
                    <h3>Consulta Excepciones</h3>
                    {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                {!! BootForm::date("inicio_fecha", "Fecha inicial : ", old("inicio_fecha"), ["width" => "col-lg-3 col-md-3"]); !!}
                            </div> 
                            <div class="col-lg-6 col-md-6">
                            {!! BootForm::date("fin_fecha", "Fecha final : ", old("fin_fecha"), ["width" => "col-lg-3 col-md-3"]); !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                {!! Form::submit('Buscar', ['class' => 'btn btn-primary']); !!}
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
                            <img class="avatar avatar-xss avatar-4by3 mr-2" 
                            src="{!! asset('materialfront/assets/svg/illustrations/print.svg') !!}" 
                            alt="Imprimir" >  
                            Imprimir
                        </a>
                        <a id="export-copy" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                            src="{!! asset('materialfront/assets/svg/illustrations/copy.svg') !!}"   
                            alt="Image Description"
                            >
                            Copiar
                        </a>
                        <a id="export-excel" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                            src="{!! asset('materialfront/assets/svg/brands/excel.svg') !!}"
                            alt="Excel">
                            Excel
                        </a>
                        <a id="export-csv" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2" 
                            src="{!! asset('materialfront/assets/svg/components/placeholder-csv-format.svg') !!}"
                            alt="CSV">
                            .CSV
                        </a>
                        <a id="export-pdf" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2" 
                            src="{!! asset('materialfront/assets/svg/brands/pdf.svg') !!}"
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
                        <input id="datatableWithSearchInput" type="search" class="form-control" placeholder="Buscar" aria-label="Buscar">
                    </div>
                    <!-- End Search -->
                    </form>
                    <!-- End Filter -->
                </div>
            </div>
            <div class="card-body">
               <!-- Table -->
            <div id="datatableWithSearchInput" class="table-responsive datatable-custom">
            <table id="exportOptionsDatatables" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                data-hs-datatables-options='{
                    "order": [],
                    "search": "#datatableWithSearchInput",
                "isResponsive": false,
                "isShowPaging": false,
                    "paging": false
                }'>
                <thead class="thead-light"> 
                    <tr>
                        <th>Folio</th>
                        <th>Fecha Solicitud</th>
                        <th>Inicio Vacaciones</th>
                        <th>Fin Vacaciones</th>
                        <th>Planta</th>
                        <th>Turno</th>
                        <th>Modulo</th>
                        <th>No. Empleado</th>
                        <th>Nombre</th>
                        <th>Puesto</th>
                        <th>Departamento</th>
                        <th>Estatus</th>
                        <th>Tipo de Excepción</th>
                        <th>VP que Autorizó</th>
                    
                        </tr>
                        
                </thead>
                <tbody>
                @foreach ($vacaciones as $vacacion)
                <tr>
                    <td>{{$vacacion->folio_vac}}</td>
                    <td>{{$vacacion->fecha_solicitud}}</td>
                    <td>{{$vacacion->fech_ini_vac}}</td>
                    <td>{{$vacacion->fech_fin_vac}}</td>
                    <td>{{$vacacion->Id_Planta}}</td>
                    <td>{{$vacacion->Id_Turno}}</td>
                    <td>{{$vacacion->Modulo}}</td>
                    <td>{{$vacacion->fk_no_empleado}}</td>
                    <td>{{$vacacion->Nom_Emp.' '.$vacacion->Ap_Pat.' '.$vacacion->Ap_Mat}}</td>
                    <td>{{$vacacion->Puesto}}</td>
                    <td>{{$vacacion->Departamento}}</td> 
                    <td>{{$vacacion->status}}</td>
                    @if($vacacion->eventualidades == 1)
                        @php
                            $des_eventualidad="EVENTUALIDAD";
                        @endphp
                    @else
                        @php
                            $des_eventualidad="PERIODO";
                        @endphp
                    @endif
                    <td>{{$des_eventualidad}}</td>
                    @foreach ($puestos as $puesto)
                        @if ($puesto->jefe_directo == $vacacion->jefe_directo)
                            <td>{{$puesto->Puesto}}</td>
                        @endif
                
                      
                    @endforeach        
                 <!--   <td class="float-center">
                        <a class="btn btn-light float-right" href="{{route('consultavacaciones.ticket', $vacacion->folio_vac)}}">Formato</a>
                    </td> -->
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
        $('.js-sortable').each(function () {
        var sortable = $.HSCore.components.HSSortable.init($(this));
        });
        // initialization of datatables
        var datatable = $.HSCore.components.HSDatatables.init($('#exportOptionsDatatables'), {
        dom: 'Bfrtip',
        buttons: [
            {
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
