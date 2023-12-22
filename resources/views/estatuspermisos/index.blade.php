@extends('layouts.main')
@section('styleBFile')
<!-- Color Box -->
<link href="{{ asset('materialfront/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('materialfront/assets/vendor/datatables.net.extensions/fixedColumns.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<style>
    .pagination{
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
                    <h3>Estatus Permisos</h3>
                    {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                {!! BootForm::date("inicio_fecha", "Fecha inicial : ", $inicio, ["width" => "col-lg-3 col-md-3"]); !!}
                            </div>
                            <div class="col-lg-6 col-md-6">
                            {!! BootForm::date("fin_fecha", "Fecha final : ", $fin, ["width" => "col-lg-3 col-md-3"]); !!}
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
                        <th>Estatus</th>
                        <th>No. Empleado</th>
                     <!--   <th>Folio</th> -->
                        <th>Permiso</th>
                        <th>Nombre</th>
                        <th>Fecha Solicitud</th>
                        <th>Inicio </th>
                        <th>Fin </th>
                     <!--   <th>Planta</th> -->
                      <!--  <th>Turno</th>-->
                     <!--   <th>Modulo</th> -->

                        <th>Puesto</th>
                        <th>Departamento</th>
                        <th>Autorizó</th>
                       <!-- <th>Excepcion</th>-->
                        <th>Firmado</th>
                        <th>Documento</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        </tr>

                </thead>
                <tbody>
                @foreach ($permisos as $permiso)
                <tr>
                    <td>{{$permiso->status}}</td>
                    <td>{{$permiso->fk_no_empleado}}</td>
                 <!--   <td>{{$permiso->folio_per}}</td> -->
                    <td>{{$permiso->permiso}}</td>
                    <td>{{$permiso->Nom_Emp.' '.$permiso->Ap_Pat.' '.$permiso->Ap_Mat}}</td>
                    @if($permiso->status == 'CANCELADO')
                    <td>{{$permiso->updated_at}}</td>
                    @else
                    <td>{{$permiso->fecha_solicitud}}</td>
                    @endif
                    <td>{{$permiso->fech_ini_per}}</td>
                    <td>{{$permiso->fech_fin_per}}</td>
                 <!--   <td>{{$permiso->Id_Planta}}</td> -->
                  <!--  <td>{{$permiso->Id_Turno}}</td> -->
                  <!--  <td>{{$permiso->Modulo}}</td> -->

                    <td>{{$permiso->Puesto}}</td>
                    <td>{{$permiso->Departamento}}</td>
                    <td>{{$permiso->autorizado_por}}</td>

                    @if ($permiso->excepcion == 1)
                 <!--       <td>{{'SI'}}</td>-->
                    @else
                     <!--   <td>{{'NO'}}</td>-->
                    @endif
                    @if ($permiso->firmado == 1)
                        <td>{{'SI'}}</td>
                    @else
                        <td>{{'NO'}}</td>
                    @endif

                    @if(auth()->user()->hasRole('Administrador Sistema') && isset($permiso->documento))
                        <td>
                             <a target="_blank" href="{{asset('/Documentos/'.$permiso->documento)}}">PDF</a>
                         </td>
                    @else
                        <td>{{$permiso->documento}}</td>
                    @endif



                    @if($permiso->tipo_per == 3 || $permiso->tipo_per == 5 || $permiso->tipo_per == 7)

                            @if (isset($permiso->documento))
                            <td><a class="btn btn-info" href="{!! route('anexardocumento',$permiso->folio_per)!!} ">Modificar Documento</a></td>
                            @else
                            <td><a class="btn btn-info" href="{!! route('anexardocumento',$permiso->folio_per)!!} ">Anexar Documento</a></td>
                            @endif

                    @else
                        <td class="float-center">
                        </td>
                    @endif
                    @if($permiso->status == 'APLICADO')
                    <td class="float-center">
                        {!! Form::model($permisos, ['method' => 'update', 'route' => ['formatopermisos.update2',$permiso->folio_per] ]) !!}
                        <a class="text-danger cancelar" style="cursor: pointer" onclick="">
                            <i class="btn btn-danger ">Cancelar</i>
                         </a>
                        {!! Form::close() !!}
                    </td>
                    @else
                    <td class="float-center">

                    </td>
                    @endif
                    @if(auth()->user()->hasRole('Administrador Sistema') )

                       <td><a class="btn btn-info" id="SignBtn" name="SignBtn" href="{{ route('formatopermisos.permisofirma', $permiso->folio_per) }}">Firma</a></td>
                      <!--  <input id="SignBtn" name="SignBtn" type="button" value="Firma" onclick="StartSign()">-->

                    @else
                        <td>{{$permiso->documento}}</td>
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

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>
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



</script>
@endsection

