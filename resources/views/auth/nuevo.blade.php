<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts/partials.head')
        <link rel="stylesheet" href="{!! asset('/css/Intimark.app.css') !!}">
    </head>
  <body class="">
    <div class="container-scroller login-register login-sidebar" style="background-image:url({!! asset('img/Intimarklogin.jpg') !!});">
        <div class="login-box card">
            <div class="card-body mt-4">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12"><!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts/partials.head')
        <link rel="stylesheet" href="{!! asset('/css/Intimark.app.css') !!}">
    </head>
  <body class="">
    <div class="container-scroller login-register login-sidebar" style="background-image:url({!! asset('img/Intimarklogin.jpg') !!});">
        <div class="login-box card">
            <div class="card-body mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group text-center">
                           <h2>Establecer contraseña</h2>
                        </div>
                    </div>
                </div>
                {!! BootForm::open(['model' => $usuario, 'update' => 'usuario.updatepassword', 'id'=>'form']) !!}
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_setpassword" value="1">

                <div class="row">
                    <div class="col-md-12">
                        <label for="nombre">Nombre:</label><br><span>{{$usuario->name}}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="email">Correo electrónico:</label><br><span>{{$usuario->email}}</span>
                    </div>
                </div>
                <br>
                <div class="row">
                    {!! BootForm::password("password", "Contraseña:", ["width" => "col-md-12"]); !!}
                    {!! Form::label('labelPassword','En la contraseña NO incluir caracteres especiales, solo letras y números',['style'=> 'color:red' ],false) !!} 
                </div>
                <div class="row">
                    {!! BootForm::password("password_confirmation", "Confirmar contraseña:", ["width" => "col-md-12"]); !!}
                </div>

                <div class="row">
                  <div class="col-md-12 text-right">
                    <button type="submit" name="enviar" value="usuario" class="btn btn-primary">Guardar</button>
                  </div>
                </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
    @include('layouts/partials.footer-scripts')
    </body>
</html>

                        <div class="form-group text-center">
                           <h2>{{ __('Reset Password') }}</h2>
                        </div>
                    </div>
                </div>
                {!! BootForm::open(['route'=>'password.email','id'=>'form','class'=>'form-horizontal']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::text('email', 'Correo electrónico1:') !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::submit(__('Send Password Reset Link'),  ['class' => 'text-center btn btn-primary waves-light form-group m-t-3']) !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                             <a href="{{ route('login') }}" id="to-recover" class="btn-link text-primary pull-right"><i class="fa fa-user m-r-5"></i>
                                 Acceder
                              </a>
                         </div>
                    </div>

                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
    @include('layouts/partials.footer-scripts')
    </body>
</html>
