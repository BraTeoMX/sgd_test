@extends('layouts.main')
@section('styleBFile')
    <!-- Color Box -->
    <link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">
    <link href="{{ asset('materialfront/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Registro de usuario</h3>
        </div>
        <div class="card-body">
            {!! BootForm::open(['model' => $usuario, 'store' => 'usuario.store', 'update' => 'usuario.update', 'id'=>'form']) !!}
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    {!! BootForm::text('name', 'Nombre:', old('name'), ['maxlength'=>'200']) !!}
                </div>
                <div class="col-lg-6 col-md-6">
                {!! BootForm::text('no_empleado', 'Número de Empleado:', old('no_empleado'), ['maxlength'=>'50']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    {!! BootForm::email("email", "Correo electrónico:", old("email"), ['maxlength'=>'200']); !!}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    {!! BootForm::text('puesto', 'Puesto:', old('puesto'), ['maxlength'=>'200']) !!}
                </div>
            </div>
            @php
                $values = ($usuario->exists) ? $usuario->roles->pluck('name')->toArray() : [];
                $values = (filled($values)) ? $values : [];
            @endphp
           <div class="row">
                <div class="col-md-12">
                    {!! BootForm::checkboxes('rol[]', 'Rol(es): *', $roles, old('rol', $values),false, ["class"=>'i-checks']) !!}
                </div>
            </div>
                    
            @if ($usuario->exists)
                <div class="row">
                    <div class="col-md-12">
                        {!! BootForm::label("inactivo", "Desactivar cuenta:"); !!}
                        {!! BootForm::checkboxElement("inactivo", false, 'X', old('inactivo'), false, ["class"=>'i-checks']); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        {!! BootForm::label("fecha_ultimo_acceso", "Fecha de último acceso:"); !!}
                        {{optional($usuario->fecha_ultimo_acceso)->format('d/m/Y')}}
                    </div>
                    <div class="col-lg-6 col-md-6">
                        {!! BootForm::label("fecha_ultima_notificacion", "Fecha de envio de correo electrónico:"); !!}
                        {{optional($usuario->fecha_ultima_notificacion)->format('d/m/Y')}}
                    </div>
                </div>
            @endif
                
            <div class="row">
                <div class="col-md-12 text-left">
                    <button type="submit" name="enviar" value="usuario" class="btn btn-primary">Guardar</button>
                    <a href="{!! route('usuario.index') !!}" class="btn btn-light">Cancelar</a>
                </div>
            </div>
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
@section('scriptBFile')
<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script type="text/javascript" src="{{ asset('colorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('materialfront/assets/vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('materialfront/assets/vendor/select2/dist/js/select2.js') }}"></script>
<script>
  $(document).ready(function() {
    //$.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" })
    $('.js-select2-custom').each(function () {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });
  });
</script>
{!! $validator !!}
@endsection
