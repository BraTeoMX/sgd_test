@foreach ($incapacidad as $per)
    @component('mail::message')
        <b>Estimado (a) colaborador (a).</b>
        <div>
            <p>El presente correo es para informarte que el colaborador numero <b>{{ $per->fk_empleado }}</b> tiene una
                incapacidad de <b>{{ $per->dias }}</b> dias, iniciando en dia <b>{{ $per->fecha_inicio }}</b>
            <p>! Saludos Cordiales !</p>
        </div>
    @endcomponent
@break
@endforeach
