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
                           <h2>Registro de Usuario</h2>
                        </div>
                    </div>
                </div>
                {!! BootForm::open(['route'=>'login','id'=>'loginform','class'=>'form-horizontal']) !!}
             
              
                <div class="row">
                    <div class="col-md-12">
                    {!! BootForm::text('no_empleado', 'Número de Empleado:') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    {!! BootForm::text('nombre', 'Nombre:') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    {!! BootForm::text('correo', 'Correo Institucional:') !!}
                </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                    {!! BootForm::password("password", "Contraseña:", ["width" => "col-md-12"]); !!}
                    {!! Form::label('labelPassword','En la contraseña NO incluir caracteres especiales, solo letras y números',['style'=> 'color:red' ],false) !!} 
                </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    {!! BootForm::password("password_confirmation", "Confirmar contraseña:", ["width" => "col-md-12"]); !!}
                </div>
                </div>
                <div class="row">
                  <div class="col-md-12 text-right">
                    <a class="text-danger guardar" style="cursor: pointer" onclick="">
                                                            <i class="btn btn-primary ">Guardar</i>
                                                        </a>
                    <a href="{!! route('home') !!}" class="btn btn-light">Cancelar</a>
                  </div>
                </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
    @include('layouts/partials.footer-scripts')
    </body>
</html>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>


<script>
   
   $('.guardar').on('click', function(event) {
    if($('#password').val() == ''){
        
    Swal.fire({
                    
                    title: '',
                        text: "Estimado colaborador, la contraseña no puede estar vacía.",
                        imageUrl: 'img/logo.png',
                        imageWidth: 400,
                        imageHeight: 200,
                        imageAlt: 'Custom image',
                        //icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar!'
                    })
                }else{
                  id = $('#no_empleado').val()+'|'+$('#nombre').val()+'|'+$('#correo').val()+'|'+$('#password').val();
                    Swal.fire({
                    title: '',
                        text: "La cuenta fue registrada satisfactoriamente.",
                        imageUrl: 'img/logo.png',
                        imageWidth: 400,
                        imageHeight: 200,
                        imageAlt: 'Custom image',
                        //icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar!'
                    }).then((result) => {
                         var url = "{{route('validaregistro', ':id')}}";
                         url = url.replace(':id', id);
	                        location.href = url;
                    })
                }
       });
    
        $('#password').change(function () {
            if($('#password').val().length < 8){
                Swal.fire({
                        title: '',
                        text: "Estimado colaborador, la contraseña debe de ser mínimo de 8 dígitos.",
                        imageUrl: 'img/logo.png',
                        imageWidth: 400,
                        imageHeight: 200,
                        imageAlt: 'Custom image',
                        //icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar!'
                        }).then((result) => {
                            $('#password').val('');
                        
                  });
            }
      
        });   
         

        $('#password_confirmation').change(function () {
            if($('#password').val() != $('#password_confirmation').val()){
                Swal.fire({
                        title: '',
                        text: "Estimado colaborador, las contraseñas no coinciden.",
                        imageUrl: 'img/logo.png',
                        imageWidth: 400,
                        imageHeight: 200,
                        imageAlt: 'Custom image',
                        //icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar!'
                        }).then((result) => {
                            $('#password').val('');
                            $('#password_confirmation').val('');
                        
                  });
            }else{
                if($('#no_empleado').val()=='' || $('#name').val()== '' || $('#name').val(correo)== ''){
                    Swal.fire({
                        title: '',
                        text: "Estimado colaborador, algunos datos no fueron ingresados, favor de verificar.",
                        imageUrl: 'img/logo.png',
                        imageWidth: 400,
                        imageHeight: 200,
                        imageAlt: 'Custom image',
                        //icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar!'
                        }).then((result) => {
                            $('#password').val('');
                            $('#password_confirmation').val('');
                        
                  });
                }
            }
      
        });   
         

      
      

        
 
</script>

