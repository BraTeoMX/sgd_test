@foreach($permisos as $per)
    @component('mail::message')
        <b>Estimado (a)  {{ $per->Nom_Emp }}.</b>
        <div>
            <p>El presente correo es para informarte que tu solicitud de permiso con folio <b>{{ $per->folio_per }}</b>, fue <b>APROBADA</b>.
            <p>Disfruta de tu permiso correspondiente al periodo <b>{{ $per->fech_ini_per }}</b> al <b>{{ $per->fech_fin_per }}</b>.</p>
            <b></b>
            <p>! Saludos Cordiales !</p>
                            
        
    @endcomponent
@endforeach