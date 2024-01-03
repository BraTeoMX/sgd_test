@extends('layouts.main')

@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('materialfront/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('materialfront/assets/vendor/datatables.net.extensions/fixedColumns.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="card" style="height: auto; width: auto;">
    <div class="card-header">
        <h1>Generar reporte {{ $nombre }}</h1>
    </div>
    <div class="container">
        @if (auth()->user()->hasRole('Administrador Sistema'))
        <form method="POST" action="{{ route('eventos.GenerarReporte') }}">
            @csrf
            <div class="form-group">
                <input type="hidden" name="mesSeleccionado" id="mesSeleccionado">
                <label for="tipo_evento">Elige el evento al que desees generar tu reporte</label>
                <div class="d-flex align-items-start">
                    <div class="col-lg-6 col-md-6">
                        <select name="tipo_evento" id="tipo_evento" class="form-control select-custom">
                            <option value="" disabled selected>Selecciona una opción</option>
                            @foreach ($eventos as $evento)
                                <option value="{{ $evento->id_evento }}" data-nombre="{{ $evento->tipo_evento }}">{{ $evento->tipo_evento }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <select name="created_at" id="created_at" class="form-control">
                            <option value="">Selecciona un mes</option>
                            @foreach ($mesesSeleccion as $mes)
                                <option value="{{ $mes->fecha }}">{{ $mes->fecha }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="registrarBtn">
                Generar reporte PDF
                <img class="avatar avatar-xss avatar-4by3 mr-2" src="{!! asset('materialfront/assets/svg/brands/pdf.svg') !!}" alt="PDF">
            </button>
            <button type="submit" name = "excel" class="btn btn-primary" id="registrarBtnExcel">
                Generar reporte Excel
                <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{ asset('materialfront/assets/svg/brands/excel.svg') }}" alt="Excel">
            </button>
        </form>

        <br>
        @endif
        @if (auth()->user()->hasRole('Seguridad e Higiene'))
        <h1>Entrega de Papel</h1>
        <form method="POST" action="{{ route('eventos.GenerarReportePapel') }}">
            @csrf
            <div >
                <select name="created_at" id="created_at" class="form-control">
                    <option value="">Selecciona un mes</option>
                    @foreach ($mesesSeleccionPapel as $mes)
                        <option value="{{ $mes->fecha }}">{{ $mes->fecha }}</option>
                    @endforeach
                </select>
            </div>
            <br>
            <button type="submit" class="btn btn-primary" id="registrarBtnPapel">
                Generar reporte PDF
                <img class="avatar avatar-xss avatar-4by3 mr-2" src="{!! asset('materialfront/assets/svg/brands/pdf.svg') !!}" alt="PDF">
            </button>
            <button type="submit" name = "excel" class="btn btn-primary" id="registrarBtnExcelPapel">
                Generar reporte Excel
                <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{ asset('materialfront/assets/svg/brands/excel.svg') }}" alt="Excel">
            </button>
        </form>
        <br>
        <br>
        <br>
        @endif

    </div>
</div>
@endsection

@section('scriptBFile')
    <!-- Tus scripts existentes aquí -->
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
        $(document).ready(function () {
            var tipoEventoSelect = $('#tipo_evento');
            var fechaSelect = $('#created_at');

            tipoEventoSelect.on('change', function () {
                var selectedEvento = tipoEventoSelect.val();

                if (selectedEvento) {
                    $.ajax({
                        url: '/obtener-meses/' + selectedEvento,
                        type: 'GET',
                        success: function (data) {
                            fechaSelect.empty().append($('<option>', { value: '', text: 'Selecciona un mes' }));
                            $.each(data, function (index, value) {
                                fechaSelect.append($('<option>', { value: value, text: value }));
                            });
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    fechaSelect.empty().append($('<option>', { value: '', text: 'Selecciona un mes' }));
                }
            });
        });
    </script>
    <script>
        var nombre = @json($nombre);
    </script>
@endsection
