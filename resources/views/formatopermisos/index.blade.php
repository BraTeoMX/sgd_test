@extends('layouts.main')

@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h3>Solicitud de Permisos</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'formatopermisos.store', 'method' => 'POST', 'files' => true]) !!}
            @forelse($datosEmpleado as $permiso)
                {!! BootForm::hidden('id_emp', $permiso->id_empleado) !!}
                {!! BootForm::hidden('tag_emp', $permiso->No_TAG) !!}
                {!! BootForm::hidden('no_aux', auth()->user()->no_empleado) !!}
                {!! BootForm::hidden('id_dep', $permiso->Departamento) !!}
                {!! BootForm::hidden('no_permisos', $no_permiso) !!}



                <div class="row">

                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('no_empleado', 'No. EMPLEADO ', $permiso->No_Empleado, ['width' => 'col-md-3', 'readonly']) !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('nom_emp', 'NOMBRE ', $permiso->Nom_Emp, ['width' => 'col-md-3', 'readonly']) !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('ap_pat', 'APELLIDO PATERNO ', $permiso->Ap_Pat, ['width' => 'col-md-3', 'readonly']) !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('ap_mat', 'APELLIDO MATERNO ', $permiso->Ap_Mat, ['width' => 'col-md-3', 'readonly']) !!}
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('modulo_emp', 'MODULO ', $permiso->Modulo, ['width' => 'col-md-3', 'readonly']) !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <!--   {!! BootForm::text('turno', 'TURNO ', $permiso->Id_Turno, ['width' => 'col-md-3', 'readonly']) !!}-->
                        @if ($permiso->Frec_Pago == '00001')
                            {!! BootForm::text('frec_pago', 'PAGO', 'SEMANAL', ['width' => 'col-md-3', 'readonly']) !!}
                        @else
                            @if ($permiso->Frec_Pago == '00002')
                                {!! BootForm::text('frec_pago', 'PAGO', 'QUINCENAL', ['width' => 'col-md-3', 'readonly']) !!}
                            @else
                                {!! BootForm::text('frec_pago', 'PAGO', 'CONFIDENCIAL', ['width' => 'col-md-3', 'readonly']) !!}
                            @endif
                        @endif
                    </div>
                    @foreach ($puestos as $puesto)
                        @if ($puesto->id_puesto == $permiso->Puesto)
                            <div class="col-lg-3 col-md-3">
                                {!! BootForm::text('puesto', 'PUESTO ', $puesto->Puesto, ['width' => 'col-md-3', 'readonly']) !!}
                            </div>
                        @endif
                    @endforeach
                    @foreach ($departamentos as $dep)
                        @if ($dep->id_Departamento == $permiso->Departamento)
                            <div class="col-lg-3 col-md-3">
                                {!! BootForm::text('departamento', 'DEPARTAMENTO ', $dep->Departamento, ['width' => 'col-md-3', 'readonly']) !!}
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <p>TIPO DE PERMISO</p>
                        <select class='form-control' aria-label="Default select example" name="permiso" id="permiso">
                            <option value=0>{{ '--SELECCIONE--' }}</option>
                            <option value=1>{{ 'PERMISO CON GOCE DE SUELDO x DIA' }}</option>
                            <option value=2>{{ 'PERMISO SIN GOCE DE SUELDO x DIA' }}</option>
                            <option value=3>{{ 'PERMISO CON GOCE DE SUELDO x HORAS' }}</option>
                            <option value=4>{{ 'PERMISO SIN GOCE DE SUELDO x HORAS' }}</option>
                        </select>
                    </div>
                    <div class="col-lg-6 col-md-6" style="display: none" id='id_motivo' name='id_motivo'>
                        {!! BootForm::select('motivo', 'MOTIVO DE PERMISO: ', [], old('id_permiso')) !!}
                    </div>


                </div>
                <div class="row">
                    @php
                        $hoy = date('Y-m-d');
                    @endphp
                    <div class="col-lg-3 col-md-3" style="display: none" id='id_inicio_per'>
                        {!! BootForm::date('inicio_per', 'INICIO DE PERMISO', '', ['width' => 'col-md-3', 'min' => $hoy]) !!}
                    </div>
                    <div class="col-lg-3 col-md-3" style="display: none" id='id_fin_per'>
                        {!! BootForm::date('fin_per', 'FIN DE PERMISO', '', ['width' => 'col-md-3', 'min' => $hoy]) !!}
                    </div>
                    <div class="col-lg-3 col-md-3" style="display: none" id='id_dias'>
                        {!! BootForm::text('dias', 'NUMERO DE DIAS SOLICITADOS', '', ['width' => 'col-md-3', 'readonly']) !!}
                    </div>
                    <!--  <div class="col-lg-3 col-md-3">

                                               {!! BootForm::text('no_permisos', 'NO. DE PERMISOS EN EL MES ', $no_permiso, [
                                                   'width' => 'col-md-3',
                                                   'readonly',
                                               ]) !!}
                                           </div>-->

                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6" style="display: none" id='id_modo_permiso'>
                        <p>MODO DE PERMISO X HORAS</p>
                        <select class='form-control' aria-label="Default select example" name="modo_permiso"
                            id="modo_permiso">
                            <option value=0>{{ '--SELECCIONE--' }}</option>
                            <option value=1>{{ 'SOLO ENTRADA' }}</option>
                            <option value=2>{{ 'SOLO SALIDA' }}</option>
                            <option value=3>{{ 'SALIDA Y ENTRADA' }}</option>
                        </select>
                    </div>
                    <div class="col-lg-6 col-md-6" style="display: none" id='id_hora_comida'>
                        <p>EL PERMISO INVOLUCRA LA HORA DE COMIDA</p>
                        <select class='form-control' aria-label="Default select example" name="hora_comida"
                            id="hora_comida">
                            <option value=0>{{ '--SELECCIONE--' }}</option>
                            <option value=1>{{ 'MEDIA HORA' }}</option>
                            <option value=2>{{ 'UNA HORA' }}</option>
                            <option value=3>{{ 'NO APLICA' }}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    @php
                        $hoy = date('Y-m-d');

                    @endphp
                    <div class="col-lg-3 col-md-3" style="display: none" id='id_inicio_diahor'>
                        {!! BootForm::date('inicio_diahor', 'DIA DE PERMISO', '', ['width' => 'col-md-3', 'min' => $hoy]) !!}
                    </div>
                    <div class="col-lg-3 col-md-3" style="display: none" id='id_fin_hor'>
                        {!! BootForm::time('inicio_hor', 'HORA ', '', ['width' => 'col-md-3']) !!}
                    </div>
                    <div class="col-lg-3 col-md-3" style="display: none" id='id_inicio_hor'>
                        {!! BootForm::time('fin_hor', 'HORA ', '', ['width' => 'col-md-3']) !!}
                    </div>
                    <div class="col-lg-3 col-md-3" style="display: none" id='id_horas'>
                        {!! BootForm::text('horas', 'MINUTOS SOLICITADOS', '', ['width' => 'col-md-3', 'readonly']) !!}
                    </div>
                </div>
                @if (auth()->user()->hasRole('Servicio Medico'))
                    <div class="row">
                        <div class="col-lg-12 col-md-12" style="display: none" id='id_autoriza' name='id_autoriza'>
                            {!! BootForm::textarea('obs', 'OBSERVACIONES', '', ['rows' => '2', 'style' => 'rezise:true']) !!}
                        </div>

                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-6 col-md-6" style="display: none" id='id_autoriza' name='id_autoriza'>
                        {!! BootForm::select('autorizar', 'PERSONA RESPONSABLE DE AUTORIZAR: ', [], old('id_permiso')) !!}
                    </div>

                </div>

                <div class="row" style="display: none" id='id_enviar'>
                    <div class="col center">
                        <button type="submit" name="solicitar" id='solicitar' value="Solicitar permisos"
                            class="btn btn-primary">Enviar</button>
                        <a href="{!! route('home') !!}" class="btn btn-light">Cancelar</a>

                    </div>
                </div>
            @empty
                <div class="col-md-4">
                    <label for="">No existe el empleado</label>
                </div>
            @endforelse
        </div>
        {!! form::close() !!}
    </div>


@endsection



@section('scriptBFile')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>


    <script>
        $(document).ready(function() {
            if ($( "input[name*='no_permisos']" ).val() >= 1) {

                let mesActual = new Intl.DateTimeFormat('es-ES', { month: 'long'}).format(new Date());

                Swal.fire({
                    title: '',
                    text: "El Colaborador ha solicitado "+$( "input[name*='no_permisos']" ).val()+" permiso(s) durante el mes de "+mesActual,
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
                //  window.location.href = '/home';
                    /* if (result.isConfirmed) {
                        Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        )
                    }*/
                })
            }


        });


        $("#solicitar").click(function() {

            // alert( "solicitud enviada con exito." );
            Swal.fire('Solicitud enviada con exito')


        });

        $('#permiso').change(function() {

            $('#id_motivo').show();

            var tipo = $('#permiso').val();

            $.get('{{ url('/') }}/consultasajax/getmotivopermisos/' + tipo, function(data) {

                $('#motivo').empty();
                $('#motivo').append('<option value="0">' + '--SELECCIONE--' + '</option>');

                $.each(data, function(fetch, regenciesObj) {
                    $('#motivo').append('<option value="' + regenciesObj.id_permiso + '">' +
                        regenciesObj.permiso + '</option>');
                })
            });
            $('#id_inicio_per').hide();
            $('#id_fin_per').hide();
            $('#id_dias').hide();
            $('#id_inicio_diahor').hide();
            $('#id_inicio_hor').hide();
            $('#id_fin_hor').hide();
            $('#id_horas').hide();
            $('#id_modo_permiso').hide();

            $('#id_enviar').hide();

        });

        $('#motivo').change(function() {

            if ($('#motivo').val() == 35) {
                $.ajax({
                    url: 'permisos.retardos',
                    method: 'POST',
                    data: {
                        empleado: $('#no_empleado').val(),
                        _token: $('input[name="_token"]').val()
                    }
                }).done(function(res) {
                    if (res > 0) {
                        if (($('#frec_pago').val() == 'SEMANAL' && res >= 2) || ($('#frec_pago').val() ==
                                'QUINCENAL' && res >= 3)) {
                            Swal.fire({
                                title: '',
                                text: "Estimado colaborador, no puede seleccionar PSGH/Retardo por el limite de retardos mensual.",
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
                                //  window.location.href = '/home';
                                $('#motivo').val('');
                            })
                        } else {
                            $('#id_autoriza').show();
                            var tipo = $('#motivo').val() + "|" + $('#no_empleado').val() + "|" + $(
                                'input[name=no_aux]').val();
                            $.get('{{ url('/') }}/consultasajax/getautorizar/' + tipo, function(
                                data) {
                                $('#autorizar').empty();
                                //   $('#autorizar').append('<option value="0">'+'--SELECCIONE--'+'</option>');
                                $.each(data, function(fetch, regenciesObj) {
                                    $('#autorizar').append('<option value="' + regenciesObj
                                        .No_Empleado + '">' +
                                        regenciesObj.Nom_Emp + ' ' + regenciesObj
                                        .Ap_Pat + ' ' + regenciesObj
                                        .Ap_Mat + '</option>');
                                })
                            });

                            if ($('#permiso').val() == 1 || $('#permiso').val() == 2) {
                                $('#id_inicio_per').show();
                                $('#id_fin_per').show();
                                $('#id_dias').show();
                                $('#id_inicio_diahor').hide();
                                $('#id_inicio_hor').hide();
                                $('#id_fin_hor').hide();
                                $('#id_horas').hide();

                                $('#inicio_per').val("");
                                $('#fin_per').val("");
                                $('#dias').val(0);
                                $('#id_modo_permiso').hide();
                                $('#id_hora_comida').hide();

                            } else {
                                $('#id_modo_permiso').show();
                                $('#id_hora_comida').hide();
                                $('#id_inicio_per').hide();
                                $('#id_fin_per').hide();
                                $('#id_dias').hide();
                                $('#id_inicio_diahor').hide();
                                $('#id_inicio_hor').hide();
                                $('#id_fin_hor').hide();
                                $('#id_horas').hide();
                            }

                        }
                    } else {
                        $('#id_autoriza').show();
                        var tipo = $('#motivo').val() + "|" + $('#no_empleado').val() + "|" + $(
                            'input[name=no_aux]').val();
                        $.get('{{ url('/') }}/consultasajax/getautorizar/' + tipo, function(data) {
                            $('#autorizar').empty();
                            //   $('#autorizar').append('<option value="0">'+'--SELECCIONE--'+'</option>');
                            $.each(data, function(fetch, regenciesObj) {
                                $('#autorizar').append('<option value="' + regenciesObj
                                    .No_Empleado + '">' +
                                    regenciesObj.Nom_Emp + ' ' + regenciesObj.Ap_Pat +
                                    ' ' + regenciesObj
                                    .Ap_Mat + '</option>');
                            })
                        });

                        if ($('#permiso').val() == 1 || $('#permiso').val() == 2) {
                            $('#id_inicio_per').show();
                            $('#id_fin_per').show();
                            $('#id_dias').show();
                            $('#id_inicio_diahor').hide();
                            $('#id_inicio_hor').hide();
                            $('#id_fin_hor').hide();
                            $('#id_horas').hide();

                            $('#inicio_per').val("");
                            $('#fin_per').val("");
                            $('#dias').val(0);
                            $('#id_modo_permiso').hide();
                            $('#id_hora_comida').hide();

                        } else {
                            $('#id_modo_permiso').show();
                            $('#id_hora_comida').hide();
                            $('#id_inicio_per').hide();
                            $('#id_fin_per').hide();
                            $('#id_dias').hide();
                            $('#id_inicio_diahor').hide();
                            $('#id_inicio_hor').hide();
                            $('#id_fin_hor').hide();
                            $('#id_horas').hide();
                        }

                    }
                });
            } else {

                $('#id_autoriza').show();
                var tipo = $('#motivo').val() + "|" + $('#no_empleado').val() + "|" + $('input[name=no_aux]').val();
                $.get('{{ url('/') }}/consultasajax/getautorizar/' + tipo, function(data) {
                    $('#autorizar').empty();
                    //   $('#autorizar').append('<option value="0">'+'--SELECCIONE--'+'</option>');
                    $.each(data, function(fetch, regenciesObj) {
                        $('#autorizar').append('<option value="' + regenciesObj.No_Empleado + '">' +
                            regenciesObj.Nom_Emp + ' ' + regenciesObj.Ap_Pat + ' ' +
                            regenciesObj
                            .Ap_Mat + '</option>');
                    })
                });

                if ($('#permiso').val() == 1 || $('#permiso').val() == 2) {
                    $('#id_inicio_per').show();
                    $('#id_fin_per').show();
                    $('#id_dias').show();
                    $('#id_inicio_diahor').hide();
                    $('#id_inicio_hor').hide();
                    $('#id_fin_hor').hide();
                    $('#id_horas').hide();

                    $('#inicio_per').val("");
                    $('#fin_per').val("");
                    $('#dias').val(0);
                    $('#id_modo_permiso').hide();
                    $('#id_hora_comida').hide();

                } else {
                    $('#id_modo_permiso').show();
                    $('#id_hora_comida').hide();
                    $('#id_inicio_per').hide();
                    $('#id_fin_per').hide();
                    $('#id_dias').hide();
                    $('#id_inicio_diahor').hide();
                    $('#id_inicio_hor').hide();
                    $('#id_fin_hor').hide();
                    $('#id_horas').hide();
                }
            }
        });

        $('#modo_permiso').change(function() {

            $('#id_hora_comida').show();
            $('#id_inicio_diahor').hide();
            $('#id_inicio_per').hide();
            $('#id_fin_per').hide();
            $('#id_dias').hide();
            $('#id_inicio_hor').hide();
            $('#id_fin_hor').hide();
            $('#id_horas').hide();
        });

        $('#hora_comida').change(function() {

            $('#id_inicio_diahor').show();
            $('#id_inicio_per').hide();
            $('#id_fin_per').hide();
            $('#id_dias').hide();
            $('#id_inicio_hor').hide();
            $('#id_fin_hor').hide();
            $('#id_horas').hide();
        });

        $('#inicio_diahor').change(function() {
            if ($('#modo_permiso').val() == 1) {
                $('#id_inicio_per').hide();
                $('#id_fin_per').hide();
                $('#id_dias').hide();
                $('#id_inicio_hor').show();
                $('#id_fin_hor').hide();
                $('#id_horas').show();
                $('#inicio_hor').val("08:00");
                $('#fin_hor').val("");
                $('#horas').val(0);

            } else {
                if ($('#modo_permiso').val() == 2) {
                    $('#id_inicio_per').hide();
                    $('#id_fin_per').hide();
                    $('#id_dias').hide();
                    $('#id_inicio_hor').hide();
                    $('#id_fin_hor').show();
                    $('#id_horas').show();
                    $('#inicio_hor').val("");
                    const numeroDia = new Date($('#inicio_diahor').val()).getDay();

                    if (numeroDia == 4) {
                        $('#fin_hor').val("14:00");
                    } else {
                        $('#fin_hor').val("19:00");
                    }
                    $('#horas').val(0);

                } else {
                    if ($('#modo_permiso').val() == 3) {
                        $('#id_inicio_per').hide();
                        $('#id_fin_per').hide();
                        $('#id_dias').hide();
                        //                 $('#id_inicio_diahor').show();
                        $('#id_inicio_hor').show();
                        $('#id_fin_hor').show();
                        $('#id_horas').show();
                        $('#inicio_hor').val("");
                        $('#fin_hor').val("");
                        $('#horas').val(0);
                    }
                }
            }
        });

        $('#inicio_per').change(function() {
            $('#id_dias').show();
            $('#id_fin_per').show();
            $('#fin_per').val("");
            $('#dias').val(0);
            $('#id_enviar').hide();

            //verificar dias no laborables
            /*
            $.ajax({

                url: 'permisos.getajax',
                method: 'POST',
                data:{
                    fecha: $('#inicio_per').val(),
                    pago: $('#frec_pago').val(),
                    _token:$('input[name="_token"]').val()
                }
            }).done(function(res){

                if(res>0){
                    Swal.fire({
                        title: '',
                        text: "Estimado colaborador, la fecha seleccionada pertenece a un día no laborable o bloqueado por el Administrador.",
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
                            $('#inicio_per').val('');
                             $('#id_enviar').hide();
                             $('#id_fin_per').hide();
                             $('#id_dias').hide();


                  })
                }else{
                    $('#id_fin_per').show();
                    $('#id_dias').show();

                };
            });

            */

        });

        $('#fin_per').change(function() {

            if ($('#motivo').val() == 8 || ($('#motivo').val() >= 11 && $('#motivo').val() <= 17)) {

                if ($('#motivo').val() == 17) {
                    const numeroDia = new Date($('#fin_per').val()).getDay();
                    if (numeroDia == 0 || numeroDia == 4) {
                        Swal.fire({
                            title: '',
                            text: "Estimado colaborador, una suspensión no puede ser ingresada para los dias lunes o viernes.",
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
                            //  window.location.href = '/home';
                            $('#inicio_per').val('');
                            $('#fin_per').val('');
                            $('#dias').val('');
                            $('#id_enviar').hide();

                        })
                    }

                }
                /*
                        $.ajax({

                                url: 'permisos.getajax',
                                method: 'POST',
                                data:{
                                    fecha: $('#fin_per').val(),
                                    pago: $('#frec_pago').val(),
                                    _token:$('input[name="_token"]').val()
                                }
                            }).done(function(res){

                                if(res>0){
                                    Swal.fire({
                                        title: '',
                                        text: "Estimado colaborador, la fecha seleccionada pertenece a un día no laborable o bloqueado por el Administrador.",
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
                                            $('#fin_per').val('');
                                            $('#id_enviar').hide();

                                })
                                };
                            });
                */
                $.ajax({
                    url: 'permisos.getajax3',
                    method: 'POST',
                    data: {

                        fecha: $('#inicio_per').val(),
                        modulo: $('#modulo_emp').val(),
                        departamento: $("input[name*='id_dep']").val(),
                        _token: $('input[name="_token"]').val()
                    }
                }).done(function(res) {

                    if (res > 0) {
                        Swal.fire({
                            title: '',
                            text: "Estimado colaborador, tu solicitud ha sido denegada por porcentaje de ausentismo, verificalo con tu jefe inmediato.",
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
                            //  window.location.href = '/home';
                            $('#inicio_per').val('');
                            $('#fin_per').val('');
                            $('#dias').val('');
                            $('#id_enviar').hide();

                        })
                    };
                });
            }
            var FechaI = new Date($('#inicio_per').val());
            var AnyoFecha = FechaI.getFullYear();
            var MesFecha = FechaI.getMonth() + 1;
            var DiaFecha = FechaI.getDate() + 1;

            var FechaF = new Date($('#fin_per').val());
            var AnyoFecha2 = FechaF.getFullYear();
            var MesFecha2 = FechaF.getMonth() + 1;
            var DiaFecha2 = FechaF.getDate() + 1;

            var Hoy = new Date();
            var AnyoHoy = Hoy.getFullYear();
            var MesHoy = Hoy.getMonth() + 1;
            var DiaHoy = Hoy.getDate();

            var DiaSemana = DiaHoy + 7;

            var validadorFecha = 0;
            var validadorFecha2 = 0;
            var validadorFecha3 = 0;

            if (AnyoFecha < AnyoHoy) {
                validadorFecha = 1;
            } else {
                if (AnyoFecha == AnyoHoy && MesFecha < MesHoy) {
                    validadorFecha = 1;
                } else {
                    if (AnyoFecha == AnyoHoy && MesFecha == MesHoy && DiaFecha < DiaHoy) {
                        validadorFecha = 1;
                    }
                }
            }

            if (AnyoFecha > AnyoFecha2) {
                validadorFecha3 = 1;
            } else {
                if (AnyoFecha == AnyoFecha2 && MesFecha > MesFecha2) {
                    validadorFecha3 = 1;
                } else {
                    if (AnyoFecha == AnyoFecha2 && MesFecha == MesFecha2 && DiaFecha > DiaFecha2) {
                        validadorFecha3 = 1;
                    }
                }
            }


            if (AnyoFecha == AnyoHoy) {
                if (MesFecha == MesHoy && DiaFecha < DiaSemana) {
                    validadorFecha2 = 1;
                } else {
                    if (MesFecha > MesHoy) {
                        if (MesHoy == 2 && DiaSemana > DiaFecha + 28) {
                            validadorFecha2 = 1;
                        }

                    }

                }

            }

            var difM = FechaF - FechaI; // diferencia en milisegundos
            var difD = difM / (1000 * 60 * 60 * 24); // diferencia en dias
            var difD = difD + 1;
            weeks = 0;


            for (i = 1; i <= difD; i++) {

                FechaI = FechaI.valueOf() + i;
                FechaI += 1000 * 60 * 60 * 24;
                FechaI = new Date(FechaI);
                if (FechaI.getDay() == 0 || FechaI.getDay() == 6) weeks++; // agrega 1 si es sábado o domingo

            }

            difD = difD - weeks;


            $('#dias').val(difD);

            if ($('#motivo').val() == 3 && $('#dias').val() != 2) {
                Swal.fire({
                    title: '',
                    text: "Estimado colaborador, el permiso por Defunción, es por 2 dias.",
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
                    //  window.location.href = '/home';
                    $('#inicio_per').val('');
                    $('#fin_per').val('');
                    $('#dias').val('');
                    $('#id_enviar').hide();

                })
            }

            if ($('#motivo').val() == 5 && $('#dias').val() != 2) {
                Swal.fire({
                    title: '',
                    text: "Estimado colaborador, el permiso por Matrimonio, es por 2 dias.",
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
                    //  window.location.href = '/home';
                    $('#inicio_per').val('');
                    $('#fin_per').val('');
                    $('#dias').val('');
                    $('#id_enviar').hide();

                })
            }

            if ($('#motivo').val() == 7 && $('#dias').val() != 5) {
                Swal.fire({
                    title: '',
                    text: "Estimado colaborador, el permiso por Paternidad, es por 5 dias.",
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
                    //  window.location.href = '/home';
                    $('#inicio_per').val('');
                    $('#fin_per').val('');
                    $('#dias').val('');
                    $('#id_enviar').hide();

                })
            }
            $('#id_enviar').show();
        });

        $('#inicio_diahor').change(function() {
            /*
                    $.ajax({

                        url: 'permisos.getajax',
                        method: 'POST',
                        data:{
                            fecha: $('#inicio_diahor').val(),
                            pago: $('#frec_pago').val(),
                            _token:$('input[name="_token"]').val()
                        }
                    }).done(function(res){

                        if(res>0){
                            Swal.fire({
                                title: '',
                                text: "Estimado colaborador, la fecha seleccionada pertenece a un día no laborable o bloqueado por el Administrador.",
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
                                    $('#inicio_diahor').val('');
                                    $('#id_enviar').hide();
                                    $('#id_inicio_hor').hide();
                                    $('#id_fin_hor').hide();
                                    $('#id_horas').hide();

                        })
                        };
                    });
            */
            $('#id_inicio_per').hide();
            $('#id_fin_per').hide();
            $('#id_dias').hide();

            if ($('#modo_permiso').val() == 1) {
                $('#fin_hor').val("");
                $('#horas').val(0);
            } else {
                if ($('#modo_permiso').val() == 2) {
                    $('#inicio_hor').val("");
                    $('#horas').val(0);
                } else {
                    $('#fin_hor').val("");
                    $('#inicio_hor').val("");
                    $('#horas').val(0);
                }
            }

            $('#id_enviar').hide();
        });

        $('#inicio_hor').change(function() {

            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes();
            /*
                    if(time > $('#inicio_hor').val()){
                        Swal.fire({
                                 title: '',
                                 text: "Estimado colaborador, no puede ingresar permisos menores a la hora actual",
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
                                    $('#inicio_hor').val('');
                                    //$('#fin_hor').val('');
                                    $('#horas').val('');

                           })

                    }*/

            if ($('#modo_permiso').val() != 2) {
                $('#fin_hor').val("");
                $('#horas').val(0);
                $('#id_enviar').hide();
            } else {
                var hora_inicio = $('#inicio_hor').val();
                var hora_final = $('#fin_hor').val();


                // Calcula los minutos de cada hora
                var minutos_inicio = hora_inicio.split(':')
                    .reduce((p, c) => parseInt(p) * 60 + parseInt(c));
                var minutos_final = hora_final.split(':')
                    .reduce((p, c) => parseInt(p) * 60 + parseInt(c));

                // Si la hora final es anterior a la hora inicial sale
                if (minutos_final < minutos_inicio) {
                    Swal.fire({
                        title: '',
                        text: "Estimado colaborador, la hora final no puede ser menor a la hora inicial",
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
                        $('#inicio_hor').val('');
                        $('#fin_hor').val('');
                        $('#horas').val('');
                        // window.location.href = '/home';
                        /* if (result.isConfirmed) {
                            Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )
                        }*/
                    })

                }

                // Diferencia de minutos
                var diferencia = minutos_final - minutos_inicio;

                // Cálculo de horas y minutos de la diferencia
                var horas = Math.floor(diferencia / 60);
                var minutos = diferencia % 60;

                tiempo = (horas * 60) + minutos;
                if ($('#hora_comida').val() == 1) {
                    tiempo = tiempo - 30;
                } else {
                    if ($('#hora_comida').val() == 2) {
                        tiempo = tiempo - 60;
                    }
                }


                $('#horas').val(tiempo);
                $('#id_enviar').show();
            }
        });

        $('#fin_hor').change(function() {

            var hora_inicio = $('#inicio_hor').val();
            var hora_final = $('#fin_hor').val();

            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes();

            /*if(time > $('#fin_hor').val()){
                Swal.fire({
                         title: '',
                         text: "Estimado colaborador, no puede ingresar permisos menores a la hora actual",
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
                         //   $('#inicio_hor').val('');
                            $('#fin_hor').val('');
                            $('#horas').val('');

                   })

            }*/
            // Calcula los minutos de cada hora
            var minutos_inicio = hora_inicio.split(':')
                .reduce((p, c) => parseInt(p) * 60 + parseInt(c));
            var minutos_final = hora_final.split(':')
                .reduce((p, c) => parseInt(p) * 60 + parseInt(c));

            // Si la hora final es anterior a la hora inicial sale
            if (minutos_final < minutos_inicio) {
                if ($('#modo_permiso').val() == 1) {
                    console.log('entra al if del if')
                    Swal.fire({
                        title: '',
                        text: "Estimado colaborador, la hora final no puede ser menor a la hora inicial",
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
                        $('#fin_hor').val('');
                        $('#horas').val('');
                        // window.location.href = '/home';
                        /* if (result.isConfirmed) {
                            Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )
                        }*/
                    })

                } else {
                    console.log('entra al else del if')
                    Swal.fire({
                        title: '',
                        text: "Estimado colaborador, la hora final no puede ser menor a la hora inicial",
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
                        $('#inicio_hor').val('');
                        $('#fin_hor').val('');
                        $('#horas').val('');
                        // window.location.href = '/home';
                        /* if (result.isConfirmed) {
                            Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )
                        }*/
                    })
                }


            }

            // Diferencia de minutos
            var diferencia = minutos_final - minutos_inicio;

            // Cálculo de horas y minutos de la diferencia
            var horas = Math.floor(diferencia / 60);
            var minutos = diferencia % 60;

            tiempo = (horas * 60) + minutos;
            if ($('#hora_comida').val() == 1) {
                tiempo = tiempo - 30;
            } else {
                if ($('#hora_comida').val() == 2) {
                    tiempo = tiempo - 60;
                }
            }

            $('#horas').val(tiempo);
            $('#id_enviar').show();

        });
    </script>
@endsection
