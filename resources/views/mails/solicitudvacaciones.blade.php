@foreach($vacaciones as $vac)
@component('mail::message')
<b>Estimado Colaborador:</b>
<p>Te informamos que existe una nueva solicitud de vacaciones:</p>
<p>Folio : </p> <b>{{ $vac->folio_vac}}</b>
<p>Solicitante : </p><b>{{ $vac->Nom_Emp.' '.$vac->Ap_Pat.' '.$vac->Ap_Mat }}</b>
<p>Periodo : </p><b>{{ $vac->fech_ini_vac.' al '.$vac->fech_fin_vac}} </b>
<p>Solicitamos de la manera más atenta, ingrese al sistema para aprobar o denegar dicha solicitud.</p>
    <p>! Saludos cordiales ¡</p>

<div>
@component('mail::button', ['url' =>  route('vacaciones.liberarPermiso2',$vac->folio_vac),'color' => 'primary',])
        Autorizar
    @endcomponent
</div>
<hr/>
<div>
@component('mail::button', ['url' =>  route('denegarPermiso2',$vac->folio_vac),'color' => 'error',])
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
