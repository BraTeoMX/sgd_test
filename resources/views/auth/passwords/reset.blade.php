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
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group text-center">
                           <h2>{{ __('Reset Password') }}</h2>
                        </div>
                    </div>
                </div>
                {!! BootForm::open(['route'=>'usuario.passwordupdate','id'=>'form','class'=>'form-horizontal']) !!}
                    {!! BootForm::hidden("_setpassword", "1", ["class"=>"form-control"]); !!}
                     <!--<div class="row">
                        <div class="col-md-12">
                            {!! BootForm::text('email', __('E-Mail Address'), old('email'), ['autocomplete'=>'off']) !!}
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::text('usuario', 'Número de Empleado:') !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::password('password', __('Password'), ['autocomplete'=>'off']) !!}
                            {!! Form::label('labelPassword','En la contraseña NO incluir caracteres especiales, solo letras y números',['style'=> 'color:red' ],false) !!}

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::password('password_confirmation', __('Confirm Password'), ['autocomplete'=>'off']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::submit(__('Reset Password'),  ['class' => 'text-center btn btn-primary waves-light form-group m-t-3']) !!}
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
