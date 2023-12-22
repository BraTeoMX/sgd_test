<?php


    $ipaddress= \Request::ip();


echo "The user's IP address is - ".$ipaddress;
?>
@foreach($permisos as $per)
@component('mail::message')
<b>Estimado Colaborador:</b>
<p>Te informamos que existe una nueva solicitud de permiso:</p>
<p>Folio : </p> <b>{{ $per->folio_per}}</b>
<p>Tipo de Permiso : </p> <b>{{ $per->permiso}}</b>
<p>Solicitante : </p><b>{{ $per->Nom_Emp.' '.$per->Ap_Pat.' '.$per->Ap_Mat }}</b>
<p>Periodo : </p><b>{{ $per->fech_ini_per.' al '.$per->fech_fin_per}} </b>
<p>Solicitamos de la manera más atenta, su apoyo para aprobar o denegar dicha solicitud.</p>
    <p>! Saludos cordiales ¡</p>

<div>
@component('mail::button', ['url' =>  url('https://padbee.cloudjinx.com/hooks/1/timeoff?id='.$per->folio_per.'&action=APLICADO'),'color' => 'primary',])
        Aceptar
    @endcomponent

</div>
<hr/>
<br>
<div>
@component('mail::button', ['url' =>  url('https://padbee.cloudjinx.com/hooks/1/timeoff?id='.$per->folio_per.'&action=DENEGADO'),'color' => 'error',])
        Denegar
    @endcomponent

</div>
<hr/>
@endcomponent
@endforeach
<script>
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
  </script>
