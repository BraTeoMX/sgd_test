@extends('layouts.main')

@section('styleBFile')

<!-- Color Box -->
<link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">

@endsection


@section('content')

    <div class="card">
        <div class="card-header">
            <h3>Saldo de Vacaciones</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['route'=>'vacaciones.saldoempleado', 'method'=>'POST', 'files'=>TRUE ]) !!}

            <div class="row">
                <div class="col-lg-3 col-md-3">
                    {!! BootForm::text('no_empleado', 'No. de Empleado ' , null , ['id'=> 'no_empleado'] ); !!}

                </div>

            </div>
                <br>
                <div class="row" style="display" id ='id_enviar'>
                    <div class="col center">
                        <button type="submit" name="solicitar" id='solicitar' value="Solicitar saldo" class="btn btn-primary">Buscar empleado</button>
                        <a href="{!! route('home') !!}" class="btn btn-light">Cancelar</a>

                    </div>
                </div>

            {!! form::close() !!}
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table" data-page-size="50" >
                    @isset($saldo)


                        <thead style="">
                            <tr>
                                <th data-sortable="true">No. Empleado</th>
                                <th data-sortable="true">Nombre </th>
                                <th data-sortable="true">Fecha Ingreso</th>
                                <th data-sortable="true">Saldo disponible</th>


                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($saldo as $saldos)
                                <tr>
                                    <td>
                                        {{$saldos->No_Empleado}}
                                    </td>
                                    <td>
                                        {{$saldos->Ap_Pat.' '.$saldos->Ap_Mat.' '.$saldos->Nom_Emp}}
                                    </td>
                                    <td>
                                        {{$saldos->Fecha_In}}
                                    </td>
                                    @php
                                        $total_vac=$saldos->Dias_Dispo;

                                    @endphp
                                    @foreach($vacaciones as $vac)
                                        @php
                                            $prueba=substr($vac->folio_vac,-1,1);
                                        @endphp
                                            @if($prueba!='V')
                                                @php
                                                $total_vac = $total_vac - $vac->dias_solicitud;
                                            // $total_vac = $total_vac - 0;
                                                @endphp
                                            @endif
                                    @endforeach
                                    <td>
                                        {{$saldos->Dias_Dispo}}
                                    </td>

                                </tr>

                        @empty
                            <tr>
                                <td colspan="7" style="color: red; background: black;">No existe Empleado, favor de Verificar</td>
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



    <script>

        document.getElementById("no_empleado").focus();


    </script>

@endsection
