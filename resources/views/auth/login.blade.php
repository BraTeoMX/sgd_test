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
                           <h2><b>Sistema SGD </b><br><b>Control de Acceso</b></h2>
                        </div>
                    </div>
                </div>
                {!! BootForm::open(['route'=>'login','id'=>'loginform','class'=>'form-horizontal']) !!}
                <!--   <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::text('email', 'Usuario:') !!}
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::text('usuario', 'Número de Empleado:') !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::password('password', 'Contraseña:',['autocomplete'=>'off']) !!}
                          
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::submit('Entrar',  ['class' => 'text-center btn btn-primary waves-light form-group m-t-3']) !!}
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('password.request') }}" id="to-recover" class="btn-link text-primary pull-right"><i class="fa fa-lock m-r-5"></i>
                                    {{ __('¿Olvidaste tu contraseña?') }}
                                </a>
                            </div>
                        </div>    
                    </div> 
                    <div class="row">
                        <div class="col-md-12"> 
                            <div class="form-group">
                                <a  href="{!! route('usuarioregistro')!!} "id="to-recover" class="btn-link text-primary pull-right"><i class="fa fa-lock m-r-5"></i>
                               
                                    {{ __('¿Registrate') }}
                                </a>
                                
                            </div>
                        </div>
                    </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
    @include('layouts/partials.footer-scripts')
    </body>
</html>
