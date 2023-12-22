
@component('mail::message')
<b>Estimado (a) {{ $user->name }}:</b>
<p>Bienvenido a Intimark, fue designado como usuario del sistema</p>
<p>Para iniciar su registro , favor de dar clic en el botón de ingresar.</p>

<div>
    @component('mail::button', [
        'url' => url(route('usuario.setpassword', $user, false)),
        'color' => 'primary',
    ])
        Ingresar
    @endcomponent
</div>
<hr/>
@endcomponent
