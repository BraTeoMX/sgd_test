@foreach($permisos as $per)
    @component('mail::message')
        <b>Estimado Colaborador (a).</b>
        <div>
            <p>El presente correo es para informar que existe una solicitud de permiso por parte del Servicio Medico con folio <b>{{ $per->folio_per }}</b>, de <b>{{ $per->Nom_Emp.' '.$per->Ap_Pat.' '.$per->Ap_Mat }}</b>
            por el motivo de: <b>{{ $per->obs }}</b> de fecha <b>{{ $per->fech_ini_per }}</b> al <b>{{ $per->fech_fin_per }}</b>.</p>
            <b></b>
            <p>Agradecemos darle seguimiento</p>
            <p>! Saludos Cordiales !</p>
                            
        
    @endcomponent
@endforeach