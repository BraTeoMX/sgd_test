
 
@component('mail::message')
@foreach($vacaciones as $vac)

    @if($vac->status=='APLICADO')
        Estimado (a) {{ $vac->Nom_Emp }}:

        Bienvenido al Sistema Integral de Administración Intimark,  por medio del presente te informamos que tu solicitud de Vacaciones con número de folio {{ $vac->folio_vac }}
        fue  APROBADA satisfactoriamente por lo que te deseamos que disfrutes tus vacaciones correspondientes al periodo del día {{ $vac->fech_ini_vac }} al día {{ $vac->fech_fin_vac }} .

        Saludos !! .
    @else
        @if($vac->status=='DECLINADO')
            Estimado (a) {{ $vac->Nom_Emp }}:

            Bienvenido al Sistema Integral de Administración Intimark,  por medio del presente te informamos que tu solicitud de Vacaciones con número de folio {{ $vac->folio_vac }}
            correspondientes al periodo del día {{ $vac->fech_ini_vac }} al día {{ $vac->fech_fin_vac }} fue  DECLINADA para mayor información consultarlo con el jefe inmediato

            Saludos !! .
        @else
            @if($vac->status=='CANCELADO')
                Estimado (a) {{ $vac->Nom_Emp }}:

                Bienvenido al Sistema Integral de Administración Intimark,  por medio del presente te informamos que tu solicitud de Vacaciones con número de folio {{ $vac->folio_vac }}
                correspondientes al periodo del día {{ $vac->fech_ini_vac }} al día {{ $vac->fech_fin_vac }} fue  CANCELADA por no recibir respuesta por parte del jefe inmediato,
                te pedimos que ingreses una nueva solicitud para solicitar las vacaciones nuevamente.

                Saludos !! .
            @else
                @if($vac->status=='ACTIVO')
                    Bienvenido al Sistema Integral de Administración Intimark,  por medio del presente le informamos que existe una nueva solicitud de vacaciones con número de folio {{ $vac->folio_vac }}
                    correspondiente a {{ $vac->Nom_Emp.' '.$vac->Ap_Pat.' '.$vac->Ap_Mat }} para disfrutar del periodo del día {{ $vac->fech_ini_vac }} al día {{ $vac->fech_fin_vac }},
                    por lo que solicitamos de la manera más atenta, ingrese al sistema, con la finalidad de aprobar o declinar dicha solicitud.

                    Saludos !! .

                @endif    
            @endif    
        @endif
    @endif    
 @endforeach


<hr/>
@endcomponent
