@extends('layouts.main')

@section('styleBFile')

<!-- Color Box -->
<link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">

@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h3>Solicitud de Vacaciones2023</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['route'=>'vacaciones.store', 'method'=>'POST', 'files'=>TRUE ]) !!}
            @forelse($datosEmpleado as $vacacion)
                {!! BootForm::hidden('id_emp', $vacacion->id_empleado); !!}
                {!! BootForm::hidden('tag_emp', $vacacion->No_TAG); !!}
                {!! BootForm::hidden('edo_neg', $vacacion->cveci2); !!}

                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('no_empleado', 'EMPLEADO ', $vacacion->No_Empleado, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('nom_emp', 'NOMBRE ', $vacacion->Nom_Emp, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('ap_pat', 'APELLIDO PATERNO ', $vacacion->Ap_Pat, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('ap_mat', 'APELLIDO MATERNO ', $vacacion->Ap_Mat, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                </div>
                <div class="row">
                <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('fecha_in', 'FECHA DE INGRESO ', $vacacion->Fecha_In, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        @if ($vacacion->Frec_Pago=='00001')
                            {!! BootForm::text('frec_pago', 'PAGO', 'SEMANAL', ['width'=>'col-md-3','readonly']); !!}
                        @else
                            @if($vacacion->Frec_Pago=='00002')
                                {!! BootForm::text('frec_pago', 'PAGO', 'QUINCENAL', ['width'=>'col-md-3','readonly']); !!}
                            @else
                                {!! BootForm::text('frec_pago', 'PAGO', 'CONFIDENCIAL', ['width'=>'col-md-3','readonly']); !!}
                            @endif
                        @endif
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('modulo_emp', 'MODULO ', $vacacion->Modulo, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('modulo_emp', 'TURNO ', $vacacion->Id_Turno, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                </div>
                <div class="row">
                    @foreach($puestos as $puesto)
                        @if($puesto->id_puesto == $vacacion->Puesto)
                            <div class="col-lg-6 col-md-6">
                                {!! BootForm::text('puesto', 'PUESTO ', $puesto->Puesto , ['width'=>'col-md-3','readonly']); !!}
                            </div>
                        @endif
                    @endforeach
                    @foreach($departamentos as $dep)
                        @if($dep->id_Departamento == $vacacion->Departamento)
                            <div class="col-lg-6 col-md-6">
                                {!! BootForm::text('departamento', 'DEPARTAMENTO ', $dep->Departamento, ['width'=>'col-md-3','readonly']); !!}
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="row" >

                    <div class="col-lg-6 col-md-6" style="background-color: grey; color: #fff">
                    PERIODO {{ $periodos1." / ".$periodos2 }}
                    </div>
                    <div class="col-lg-6 col-md-6" style="background-color: #765341; color: #fff"">
                    PERIODO {{ $periodos3." / ".$periodos4  }}
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-2 col-md-2">
                        {!! BootForm::text('saldo_anterior', 'SALDO DISPONIBLE ', number_format($vigencia->saldo_anterior,0), ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-1 col-md-1">
                        @foreach ($parametros as $parametro)
                            @if($parametro->clave == 'eve_vac')
                                @php
                                    $valor_event = $parametro->valor;
                                @endphp
                            @endif
                        @endforeach

                        {!! BootForm::text('event_anterior', 'EVENT. ',$vigencia->event_anterior.'/'. $valor_event, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-1 col-md-1">
                         @foreach ($parametros as $parametro)
                            @if($parametro->clave == 'per_vac')
                                @php
                                    $valor_periodo = $parametro->valor;
                                @endphp
                            @endif
                        @endforeach
                        {!! BootForm::text('periodo_anterior', 'PERIODO ', $vigencia->periodo_anterior.'/'.$valor_periodo, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-2 col-md-2">
                        {!! BootForm::text('vigencia_anterior', 'VIGENCIA ', $vigencia->vigencia_anterior, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-2 col-md-2">
                        {!! BootForm::text('saldo_nuevo', 'SALDO DISPONIBLE ', number_format($vigencia->saldo_nuevo,0), ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-1 col-md-1">
                        @foreach ($parametros as $parametro)
                            @if($parametro->clave == 'eve_vac')
                                @php
                                    $valor_event = $parametro->valor;
                                @endphp
                            @endif
                        @endforeach

                        {!! BootForm::text('event_nuevo', 'EVENT. ',$vigencia->event_nuevo.'/'. $valor_event, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-1 col-md-1">
                         @foreach ($parametros as $parametro)
                            @if($parametro->clave == 'per_vac')
                                @php
                                    $valor_periodo = $parametro->valor;
                                @endphp
                            @endif
                        @endforeach
                        {!! BootForm::text('periodo_nuevo', 'PERIODO ', $vigencia->periodo_nuevo.'/'.$valor_periodo, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-2 col-md-2">
                        {!! BootForm::text('vigencia_nuevo', 'VIGENCIA ', $vigencia->vigencia_nuevo, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <p>EVENTUALIDAD</p>
                        <select class='form-control' aria-label="Default select example" name="eventualidad" id="eventualidad">
                            <option value= 0 >{{'--SELECCIONE--'}}</option>
                            <option value= 1 >{{'SI'}}</option>
                            <option value= 2 >{{'NO'}}</option>
                        </select>
                    </div>
                    @php
                        $hoy= date('Y-m-d');
                    @endphp
                    <div class="col-lg-3 col-md-3"  style="display: none" id='id_inicio_vac'>
                        {!! BootForm::date('inicio_vac', 'INICIO DE VACACIONES','',['width'=>'col-md-3', 'min'=>$hoy]) !!}
                    </div>
                    <div class="col-lg-3 col-md-3"  style="display: none" id='id_fin_vac'>
                        {!! BootForm::date('fin_vac', 'FIN DE VACACIONES','',['width'=>'col-md-3', 'min'=>$hoy]) !!}
                    </div>
                    <div class="col-lg-3 col-md-3" >
                        {!! BootForm::text('dias_laborales', 'NUMERO DE DIAS SOLICITADOS', '', ['width'=>'col-md-3','readonly']) !!}
                    </div>
                </div>
                @if(auth()->user()->hasRole('Vicepresidente')  )
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <p>PERSONA RESPONSABLE DE AUTORIZAR</p>
                            {!! BootForm::text('', ' ', strtoupper(auth()->user()->name), ['width'=>'col-md-3','readonly']); !!}
                    </div>
                </div>
                @endif
                <br>
                <div class="row" style="display: none" id ='id_enviar'>
                    <div class="col center">
                        <button type="submit" name="solicitar" id='solicitar' value="Solicitar vacaciones" class="btn btn-primary">Enviar</button>
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

        disponibles = parseFloat($('#saldo_anterior').val())+parseFloat($('#saldo_nuevo').val());

        if($('#event_anterior').val().substr(0,1) >= $('#event_anterior').val().substr(2,1)  && $('#periodo_anterior').val().substr(0,1) >= $('#periodo_anterior').val().substr(2,1) && $('#event_nuevo').val().substr(0,1) >= $('#event_nuevo').val().substr(2,1)  && $('#periodo_nuevo').val().substr(0,1) >= $('#periodo_nuevo').val().substr(2,1)){

                 Swal.fire({
                     title: '',
                     text: "Estimado colaborador, has cubierto el número de eventualidades y periodos permitidos",
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
                         window.location.href = '/home';
                     /* if (result.isConfirmed) {
                         Swal.fire(
                         'Deleted!',
                         'Your file has been deleted.',
                         'success'
                         )
                     }*/
               })
             }

        if($('#saldo_anterior').val()<=0 && $('#saldo_nuevo').val()<=0){

           Swal.fire({
                title: '',
                text: "Estimado colaborador, por el momento no cuentas con días disponibles para vacaciones",
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
                    window.location.href = '/home';
            })
        }else{

            $.ajax({
                url: 'vacaciones.getajax2',
                method: 'POST',
                data:{

                    empleado: $('#no_empleado').val(),
                    _token:$('input[name="_token"]').val()
                }
            }).done(function(res){

                if(res>0){

                    Swal.fire({
                        title: '',
                        text: "Estimado colaborador, tu cuentas con una solicitud anterior, aun sin contestar.",
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
                            window.location.href = '/home';


                  })

                };
            });
        };

        $('#eventualidad').change(function () {


            if($('#eventualidad').val() == 1){
                $('#id_inicio_vac').show();
                $('#id_fin_vac').hide();
                $('#dias_laborales').val(1);
                $('#id_enviar').hide();

                if($('#event_anterior').val().substr(0,1) >= $('#event_anterior').val().substr(2,1) && $('#event_nuevo').val().substr(0,1) >= $('#event_nuevo').val().substr(2,1)){

                    Swal.fire({
                        title: '',
                        text: "Estimado colaborador, has cubierto el número de eventualidades permitidas",
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
                            window.location.href = '/home';
                  })
                }

            } else if ($('#eventualidad').val() == 2){

                if($('#periodo_anterior').val().substr(0,1) >= $('#periodo_anterior').val().substr(2,1) && $('#periodo_nuevo').val().substr(0,1) >= $('#periodo_nuevo').val().substr(2,1)){

                    Swal.fire({
                        title: '',
                        text: "Estimado colaborador, has cubierto el número de periodos permitidos",
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
                            window.location.href = '/home';
                  })
                }

                $('#id_inicio_vac').show();
                if(disponibles==1){
                    $('#id_fin_vac').hide();
                }else{
                    $('#id_fin_vac').show();
                }
                $('#id_enviar').hide();
            }else{
                $('#id_inicio_vac').hide();
                $('#id_fin_vac').hide();
                $('#id_enviar').hide();
            }
        });



        $('#inicio_vac').change(function () {
//verificar porcentaje de ausentismo

           $.ajax({
                url: 'vacaciones.getajax3',
                method: 'POST',
                data:{

                    fecha: $('#inicio_vac').val(),
                    modulo: $('#modulo_emp').val(),
                    departamento: $( "input[name*='id_dep']" ).val(),
                    edo_neg: $( "input[name*='edo_neg']" ).val(),
                    _token:$('input[name="_token"]').val()
                }
            }).done(function(res){
            // alert(res);
                if(res>0){
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
                            window.location.href = '/home';

                  })
                };
            });
        //********************** */
            var FechaI = new Date($('#inicio_vac').val());
            var AnyoFecha = FechaI.getFullYear();
            var MesFecha = FechaI.getMonth()+1;
            var DiaFecha = FechaI.getDate()+1;

            if(DiaFecha>31){
                DiaFecha = 1;
                var MesFecha = FechaI.getMonth()+2;
            }

            var Hoy = new Date();
            var AnyoHoy = Hoy.getFullYear();
            var MesHoy = Hoy.getMonth()+1;
            var DiaHoy = Hoy.getDate();

            var DiaSemana = DiaHoy+7;

            var validadorFecha=0;
            var validadorFecha2=0;

            if (AnyoFecha < AnyoHoy){
                validadorFecha=1;
            }else{
                if (AnyoFecha == AnyoHoy && MesFecha < MesHoy){
                    validadorFecha=1;
                }else{
                    if (AnyoFecha == AnyoHoy && MesFecha == MesHoy && DiaFecha < DiaHoy){
                        validadorFecha=1;
                    }
                }
            }

          if (AnyoFecha == AnyoHoy ){
                if( MesFecha == MesHoy &&  DiaFecha < DiaSemana){
                    validadorFecha2=1;
                }else{
                    if( MesFecha > MesHoy){
                        if(MesHoy == 2 && DiaSemana > DiaFecha+28){
                            validadorFecha2=1;
                        }

                    }

                }

            }
        //verificar dias no laborables
            $.ajax({

                url: 'vacaciones.getajax',
                method: 'POST',
                data:{
                    fecha: $('#inicio_vac').val(),
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
                            $('#inicio_vac').val('');
                             $('#id_enviar').hide();


                  })
                };
            });


/************************ */

           if($('#eventualidad').val() == 1){

                if( validadorFecha==1 )
                {
                    Swal.fire({
                        title: '',
                        text: "Estimado colaborador, por favor ingresa una fecha posterior a la actual.",
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
                            $('#inicio_vac').val('');
                             $('#id_enviar').hide();


                  })

                }else{
                    $('#id_enviar').show();
                }
            }else{
                if(disponibles<=2){
                    $('#dias_laborales').val(disponibles);

                    if( validadorFecha==1 )
                    {
                        Swal.fire({
                                title: '',
                                text: "Estimado colaborador, por favor ingresa una fecha posterior a la actual.",
                                imageUrl: 'img/logo.png',
                                imageWidth: 300,
                                imageHeight: 150,
                                imageAlt: 'Custom image',
                                position: 'top',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Aceptar!'
                                }).then((result) => {
                                    $('#inicio_vac').val('');
                                    $('#id_enviar').hide();
                        })

                    }else{
                        if( validadorFecha2==1){
                            Swal.fire({
                                title: '',
                                    text: "Estimado colaborador, el periodo de vacaciones debe ser ingresado mínimo con una semana de anticipación.",
                                    imageUrl: 'img/logo.png',
                                    imageWidth: 300,
                                    imageHeight: 150,
                                    imageAlt: 'Custom image',
                                    position: 'top',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Aceptar!'
                                    }).then((result) => {
                                        $('#inicio_vac').val('');
                                         $('#id_enviar').hide();
                            })

                        }else{
                            $('#id_enviar').show();
                        }

                    }
                }else{
                    $('#dias_laborales').val(disponibles);

                    if( validadorFecha==1 )
                    {
                        Swal.fire({
                            title: '',
                                text: "Estimado colaborador, por favor ingresa una fecha posterior a la actual.",
                                imageUrl: 'img/logo.png',
                                imageWidth: 300,
                                imageHeight: 150,
                                imageAlt: 'Custom image',
                                position: 'top',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Aceptar!'
                                }).then((result) => {
                                    $('#inicio_vac').val('');
                                    $('#id_enviar').hide();
                        })

                    }else{
                        if( validadorFecha2==1){
                            Swal.fire({
                                title: '',
                                    text: "Estimado colaborador, el periodo de vacaciones debe ser ingresado mínimo con una semana de anticipación.",
                                    imageUrl: 'img/logo.png',
                                    imageWidth: 300,
                                    imageHeight: 150,
                                    imageAlt: 'Custom image',
                                    position: 'top',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Aceptar!'
                                    }).then((result) => {
                                        $('#inicio_vac').val('');
                                         $('#id_enviar').hide();
                            })

                        }else{
                            $('#id_enviar').show();
                        }

                    }
                }
            }
        });

        $('#fin_vac').change(function () {
        $.ajax({
                url: 'vacaciones.getajax',
                method: 'POST',
                data:{

                    fecha: $('#fin_vac').val(),
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
                            $('#fin_vac').val('');
                             $('#id_enviar').hide();
                  })

                }else{
                    //alert("aqui");
                };
            });
/***************************************** */

            var FechaI = new Date($('#inicio_vac').val());
            var AnyoFecha = FechaI.getFullYear();
            var MesFecha = FechaI.getMonth()+1;
            var DiaFecha = FechaI.getDate()+1;

            var FechaF = new Date($('#fin_vac').val());
            var AnyoFecha2 = FechaF.getFullYear();
            var MesFecha2 = FechaF.getMonth()+1;
            var DiaFecha2 = FechaF.getDate()+1;

            var Hoy = new Date();
            var AnyoHoy = Hoy.getFullYear();
            var MesHoy = Hoy.getMonth()+1;
            var DiaHoy = Hoy.getDate();

            var DiaSemana = DiaHoy+7;

            var validadorFecha=0;
            var validadorFecha2=0;
            var validadorFecha3=0;

            if (AnyoFecha < AnyoHoy){
                validadorFecha=1;
            }else{
                if (AnyoFecha == AnyoHoy && MesFecha < MesHoy){
                    validadorFecha=1;
                }else{
                    if (AnyoFecha == AnyoHoy && MesFecha == MesHoy && DiaFecha < DiaHoy){
                        validadorFecha=1;
                    }
                }
            }

            if (AnyoFecha > AnyoFecha2){
                validadorFecha3=1;
            }else{
                if (AnyoFecha == AnyoFecha2 && MesFecha > MesFecha2){
                    validadorFecha3=1;
                }else{
                    if (AnyoFecha == AnyoFecha2 && MesFecha == MesFecha2 && DiaFecha > DiaFecha2){
                        validadorFecha3=1;
                    }
                }
            }


            if (AnyoFecha == AnyoHoy ){
                if( MesFecha == MesHoy &&  DiaFecha < DiaSemana){
                    validadorFecha2=1;
                }else{
                    if( MesFecha > MesHoy){
                        if(MesHoy == 2 && DiaSemana > DiaFecha+28){
                            validadorFecha2=1;
                        }

                    }

                }

            }

            if( validadorFecha==1 )
            {
              Swal.fire({
                title: '',
                    text: "Estimado colaborador, por favor ingresa una fecha posterior a la actual.",
                    imageUrl: 'img/logo.png',
                    imageWidth: 300,
                     imageHeight: 150,
                     imageAlt: 'Custom image',
                     position: 'top',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!'
                    }).then((result) => {
                        $('#inicio_vac').val('');
                        $('#fin_vac').val('');
                        $('#id_enviar').hide();
                })

            }else{
               if( validadorFecha3==1 )
                {
                    Swal.fire({
                        title: '',
                        text: "Estimado colaborador, por favor ingresa una fecha posterior a la actual.",
                        imageUrl: 'img/logo.png',
                        imageWidth: 300,
                     imageHeight: 150,
                     imageAlt: 'Custom image',
                     position: 'top',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar!'
                        }).then((result) => {
                            $('#inicio_vac').val('');
                            $('#fin_vac').val('');
                            $('#id_enviar').hide();
                    })
                }else{

                    var difM = FechaF - FechaI ; // diferencia en milisegundos
                    var difD = difM / (1000 * 60 * 60 * 24); // diferencia en dias
                    var difD = difD +1;
                    weeks = 0;


                    for(i = 1; i <= difD; i++){

                        FechaI = FechaI.valueOf()+i;
                        FechaI += 1000 * 60 * 60 * 24;
                        FechaI = new Date(FechaI);
                        if (FechaI.getDay () == 0 || FechaI.getDay () == 6) weeks ++; // agrega 1 si es sábado o domingo

                    }

                    difD = difD - weeks;


                    $('#dias_laborales').val(difD);
                    var solicitados = +$('#dias_laborales').val();

                    if (disponibles==1)
                    {
                        Swal.fire({
                            title: '',
                            text: "Estimado colaborador, solo se le otorgara un dia de vacaciones como remanente.",
                            imageUrl: 'img/logo.png',
                            imageWidth: 300,
                            imageHeight: 150,
                            imageAlt: 'Custom image',
                            position: 'top',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aceptar!'
                            }).then((result) => {
                                $('#dias_laborales').val('');
                                $('#inicio_vac').val('');
                                $('#fin_vac').val('');
                                $('#fin_vac').hide();
                                $('#id_fin_vac').hide();
                        })

                    }else{

                        if (solicitados <= 2){
                            Swal.fire({
                                title: '',
                                text: "Estimado colaborador, el periodo vacacional debe considerar como mínimo 3 días, favor de verificar",
                                imageUrl: 'img/logo.png',
                                imageWidth: 300,
                                imageHeight: 150,
                                imageAlt: 'Custom image',
                                position: 'top',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Aceptar!'
                                }).then((result) => {
                                    $('#inicio_vac').val('');
                                    $('#fin_vac').val('');
                                    $('#dias_laborales').val('');
                                    $('#dias_laborales').focus();
                                    $('#id_enviar').hide();
                            })

                        }else{
                            if (solicitados > disponibles){
                                if(disponibles != 0){
                                    //alert( "!! Los dias Solicitados: "+solicitados+" excede el número de dias disponibles: "+disponibles+", favor de verificar." );
                                    Swal.fire({
                                        title: '',
                                        text: "Estimado colaborador, el periodo de días solicitados "+solicitados+" excede el número de dias disponibles: "+disponibles+", favor de verificar",
                                        imageUrl: 'img/logo.png',
                                        imageWidth: 300,
                                        imageHeight: 150,
                                        imageAlt: 'Custom image',
                                        position: 'top',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Aceptar!'
                                        }).then((result) => {
                                            $('#inicio_vac').val('');
                                            $('#fin_vac').val('');
                                            $('#dias_laborales').val('');
                                            $('#dias_laborales').focus();
                                           $('#id_enviar').hide();
                                    })

                                }else{
                                    Swal.fire({
                                        title: '',
                                            text: "Estimado colaborador, por el momento no cuentas con días disponibles para vacaciones",
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
                                                window.location.href = 'vacaciones.solicitarvacaciones';

                                    })
                                }
                            }else{
                                if(validadorFecha2==1){
                                        Swal.fire({
                                            title: '',
                                        text: "Estimado colaborador, el periodo de vacaciones debe ser ingresado mínimo con una semana de anticipación.",
                                        imageUrl: 'img/logo.png',
                                        imageWidth: 300,
                                        imageHeight: 150,
                                        imageAlt: 'Custom image',
                                        position: 'top',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Aceptar!'
                                        }).then((result) => {
                                            $('#inicio_vac').val('');
                                            $('#fin_vac').val('');
                                            $('#dias_laborales').val('');
                                            $('#dias_laborales').focus();
                                            $('#id_enviar').hide();
                                    })

                                }else{
                                    $('#id_enviar').show();
                                }
                            }
                        }
                    }
                }
            }
        });


    });


 $( "#solicitar" ).click(function() {

  // alert( "solicitud enviada con exito." );
    Swal.fire('Solicitud enviada con exito')


 });


</script>

@endsection
