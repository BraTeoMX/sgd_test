@extends('layouts.main')

@section('styleBFile')

<!-- Color Box -->
<link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">

@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h3>Solicitud de Vacaciones</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['route'=>'vacaciones.store', 'method'=>'POST', 'files'=>TRUE ]) !!}
            @forelse($datosEmpleado as $vacacion)
                {!! BootForm::hidden('id_emp', $vacacion->id_empleado); !!}
                {!! BootForm::hidden('tag_emp', $vacacion->No_TAG); !!}

                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('id_planta', 'PLANTA ', $vacacion->Id_Planta, ['width'=>'col-md-6','readonly']); !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('no_empleado', 'EMPLEADO ', $vacacion->No_Empleado, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('modulo_emp', 'MODULO ', $vacacion->Modulo, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('modulo_emp', 'TURNO ', $vacacion->Id_Turno, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        {!! BootForm::text('nom_emp', 'NOMBRE ', $vacacion->Nom_Emp, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-4 col-md-4">
                        {!! BootForm::text('ap_pat', 'APELLIDO PATERNO ', $vacacion->Ap_Pat, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-4 col-md-4">
                        {!! BootForm::text('ap_mat', 'APELLIDO MATERNO ', $vacacion->Ap_Mat, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                </div>
                <div class="row">
                    @foreach($puestos as $puesto)
                        @if($puesto->id_Puesto == $vacacion->Puesto)
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
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        {!! BootForm::text('fecha_in', 'FECHA DE ANTIGUEDAD ', $vacacion->Fecha_In, ['width'=>'col-md-3','readonly']); !!}
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
                    @php
                    $total_vac=$vacacion->Dias_Dispo;

                @endphp
                @foreach($vacaciones as $vac)
                    @php
                        $prueba=substr($vac->folio_vac,-1,1);
                    @endphp
                        @if($prueba!='V')
                            @php
                           // $total_vac = $total_vac - $vac->dias_solicitud;
                            $total_vac = $total_vac - 0;
                            @endphp
                        @endif


                @endforeach
                    <div class="col-lg-2 col-md-2">
                        {!! BootForm::text('saldo_dias', 'SALDO DE DIAS', number_format($total_vac,0), ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-2 col-md-2">
                        {!! BootForm::text('dias_reservados', 'DIAS RESERVADOS', number_format(0,0), ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-2 col-md-2">
                        {!! BootForm::text('dias_disponibles', 'DIAS DISPONIBLES', number_format($total_vac,0), ['width'=>'col-md-3','readonly']); !!}
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
                    <div class="col-lg-3 col-md-3">
                        @foreach ($parametros as $parametro)
                            @if($parametro->clave == 'eve_vac')
                                @php
                                    $valor_event = $parametro->valor;
                                @endphp
                            @endif
                        @endforeach

                        {!! BootForm::text('eventualidades', 'EVENTUALIDADES',$eventualidad.'/'. $valor_event, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                         @foreach ($parametros as $parametro)
                            @if($parametro->clave == 'per_vac')
                                @php
                                    $valor_periodo = $parametro->valor;
                                @endphp
                            @endif
                        @endforeach
                        {!! BootForm::text('periodos', 'PERIODOS', $periodo.'/'.$valor_periodo, ['width'=>'col-md-3','readonly']); !!}
                    </div>
                </div>
                <div class="row">
                    @php
                        $hoy= date('Y-m-d');
                    @endphp
                    <div class="col-lg-3 col-md-3"  style="display: none" id='id_inicio_vac'>
                        {!! BootForm::date('inicio_vac', 'INICIO DE VACACIONES','',['width'=>'col-md-3']) !!}
                    </div>
                    <div class="col-lg-3 col-md-3"  style="display: none" id='id_fin_vac'>
                        {!! BootForm::date('fin_vac', 'FIN DE VACACIONES','',['width'=>'col-md-3']) !!}
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
              <!--  <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <p>PERSONA RESPONSABLE DE AUTORIZAR</p>

                            <select class='form-control' aria-label="Default select example" name="idjefe" id="idjefe">
                                @forelse ($datosJefe as $i)
                                    @if($i->id_puesto_solicitante == $i->id_jefe )
                                        <option value={{$i->No_Empleado}}>{{$i->Nom_Emp.' '.$i->Ap_Pat.' '.$i->Ap_Mat}}</option>
                                    @endif
                                @empty
                                @endforelse
                            </select>

                    </div>
                </div>    -->
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


        $('#eventualidad').change(function () {
            if($('#eventualidad').val() == 1){
                $('#id_inicio_vac').show();
                $('#id_fin_vac').hide();
                $('#dias_laborales').val(1);
                $('#id_enviar').hide();

                if($('#eventualidades').val().substr(0,1) == $('#eventualidades').val().substr(2,1) ){

                }

            } else if ($('#eventualidad').val() == 2){

                if($('#periodos').val().substr(0,1) == $('#periodos').val().substr(2,1) ){

                }
                $('#id_inicio_vac').show();
                if($('#dias_disponibles').val()==1){
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
            var FechaI = new Date($('#inicio_vac').val());
            var AnyoFecha = FechaI.getFullYear();
            var MesFecha = FechaI.getMonth()+1;
            var DiaFecha = FechaI.getDate()+1;

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
                    validadorFecha=0;
                }else{
                    if (AnyoFecha == AnyoHoy && MesFecha == MesHoy && DiaFecha < DiaHoy){
                       // validadorFecha=1;
                    }
                }
            }

;           if (AnyoFecha == AnyoHoy ){
                if( MesFecha == MesHoy &&  DiaFecha < DiaSemana){
                  //  validadorFecha2=1;
                }else{
                    if(DiaFecha < DiaSemana){
                        validadorFecha2=1;
                    }
                }

            }


           if($('#eventualidad').val() == 1){

                if( validadorFecha==1 )
                {

                }else{
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
                    $('#id_enviar').show();
                }
            }else{
                if($('#dias_disponibles').val()==1){
                    $('#dias_laborales').val(1);

                    if( validadorFecha==1 )
                    {


                    }else{
                        if( validadorFecha2==1){


                        }else{
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
                            $('#id_enviar').show();
                        }

                    }
                }else{
                    $('#dias_laborales').val();

                    if( validadorFecha==1 )
                    {

                    }else{
                        if( validadorFecha2==1){


                        }else{
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
                            $('#id_enviar').show();
                        }

                    }
                }
            }
        });

        $('#fin_vac').change(function () {

            var disponibles = +$('#dias_disponibles').val();

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

         /*   if (AnyoFecha < AnyoHoy){
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
                    if(DiaFecha < DiaSemana){
                        validadorFecha2=1;
                    }
                }

            }*/
            $('#id_enviar').show();
            if( validadorFecha==1 )
            {


            }else{
               if( validadorFecha3==1 )
                {

                }else{

                    var difM = FechaF - FechaI ; // diferencia en milisegundos
                    var difD = difM / (1000 * 60 * 60 * 24); // diferencia en dias
                    var difD = difD +1;

                    weeks = 0;
                    for(i = 0; i < difD; i++){
                        if (FechaI.getDay () == 5 || FechaI.getDay () == 6) weeks ++; // agrega 1 si es sábado o domingo
                        FechaI = FechaI.valueOf();
                        FechaI += 1000 * 60 * 60 * 24;
                        FechaI = new Date(FechaI);
                    }

                    difD = difD - weeks;

                    $('#dias_laborales').val(difD);
                    var solicitados = +$('#dias_laborales').val();

                    if (disponibles==1)
                    {


                    }else{
                        if (solicitados == 1){

                        }else{
                          if (solicitados <= 2){
                          //  alert( "El periodo de vacaciones debe de ser de 2 dias o mayor" );
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
                                if(validadorFecha2==1){


                                }else{
                                    $.ajax({

url: 'vacaciones.getajax',
method: 'POST',
data:{
    fecha: $('#fin_vac').val(),
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
            $('#fin_vac').val('');
            $('#id_enviar').hide();


})
};
});
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
