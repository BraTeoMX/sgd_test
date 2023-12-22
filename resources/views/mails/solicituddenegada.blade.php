@foreach($vacaciones as $vac)
    @component('mail::message')
        <b>Estimado (a)  {{ $vac->Nom_Emp }}.</b>
        <div>
            <p>El presente correo es para informarte que tu solicitud de vacaciones con folio <b>{{ $vac->folio_vac }}</b>, fue <b>DENEGADA</b>, para mayor información consultarlo con tu Jefe Inmediato</p>
            <b></b>
            <p>! Saludos Cordiales !</p>
                            
        
    @endcomponent
@endforeach