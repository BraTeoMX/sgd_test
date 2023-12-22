@foreach($vacaciones as $vac)
    @component('mail::message')
        <b>Estimado (a)  {{ $vac->Nom_Emp }}.</b>
        <div>
            <p>El presente correo es para informarte que tu solicitud de vacaciones con folio <b>{{ $vac->folio_vac }}</b>, fue <b>APROBADA</b>.
            <p>Disfruta de tus vacaciones correspondientes al periodo <b>{{ $vac->fech_ini_vac }}</b> al <b>{{ $vac->fech_fin_vac }}</b>.</p>
            <b></b>
            <p>! Saludos Cordiales !</p>


    @endcomponent
@endforeach
