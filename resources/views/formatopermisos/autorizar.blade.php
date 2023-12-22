@extends('layouts.main')
@section('styleBFile')

<!-- Color Box -->
<link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">

@endsection
@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Autorizacion de Permisos</h3>
    </div>

    <div class="card-body">

        {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
       
        {!! BootForm::close() !!}
        
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table toggle-circle">

                        <thead>
                                                 
                            <th></th>
                            <th></th>
                            <th>Folio</th>
                            <th>Fecha de solicitud</th>
                            
                            <th>Inicio permisos</th>
                            <th>Fin permisos</th>
                            <th>Hora Inicial</th>
                            <th>Hora Final</th>
                            <th>Motivo</th>
                            <th>No. Empleado</th>
                            <th>Nombre</th>
                            <th>Modulo</th>
                           
                            
                           <!-- <th>Puesto</th>
                            <th>Departamento</th>-->
                       
                        </thead>
                        <tbody>
                           
                            @foreach ($permisos2 as $permiso)
                                           
                                 <tr>
                                        <td><a class="btn btn-info" href="{!! route('formatopermisos.liberarPermisos',$permiso->folio_per)!!} ">Liberar</a></td>
                                           <td class="float-center">
                                               {!! Form::model($permisos2, ['method' => 'update', 'route' => ['formatopermisos.update',$permiso->folio_per] ]) !!}
                                               <a class="text-danger eliminar" style="cursor: pointer" onclick="">
                                                   <i class="btn btn-danger ">Denegar</i>
                                               </a>
                                               {!! Form::close() !!}
                                            </td>
                                                    
                                            <td>{{$permiso->folio_per}}</td>
                                            <td>{{$permiso->fecha_solicitud}}</td>
                                            <td>{{$permiso->fech_ini_per}}</td>
                                            <td>{{$permiso->fech_fin_per}}</td> 
                                            <td>{{$permiso->fech_ini_hor}}</td> 
                                            <td>{{$permiso->fech_fin_hor}}</td> 
                                            <td>{{$permiso->permiso}}</td> 
                                            <td>{{$permiso->fk_no_empleado}}</td>
                                          
                                            <td>{{$permiso->Nom_Emp.' '.$permiso->Ap_Pat.' '.$permiso->Ap_Mat}}</td>
                                            <td>{{$permiso->Modulo}}</td>
                                        <!--    <td>{{$permiso->Puesto}}</td>
                                            <td>{{$permiso->Departamento}}</td>-->
                                              
                                 </tr>
                                 
                            @endforeach
                            
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 

@endsection
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>

@section('scriptBFile')
    <script>
        $(document).ready(function() {
            if($('#existe').val()==1){
                Swal.fire({
                    title: '',
                    text: "Estimado colaborador, tu solicitud fue denegada por porcentaje de ausentismo.",
                    imageUrl: 'img/logo.png',
                    imageWidth: 400,
                    imageHeight: 200,
                    imageAlt: 'Custom image',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!'
                }).then((result) => {
                  //  $(this).closest('form').submit();
                    /* if (result.isConfirmed) {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                   }*/
                })
            };
            

            $('.eliminar').on('click', function(event) {
               

                Swal.fire({
                    title: 'Estimado colaborador, esta seguro de denegar la solicitud?',
                    text: "",
                    //icon: 'warning',
                    imageUrl: 'img/logo.png',
                    imageWidth: 400,
                    imageHeight: 200,
                    imageAlt: 'Custom image',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                        'La solicitud ha sido Denegada satisfactoriamente!'
                        )
                        $(this).closest('form').submit();
                    }
                    })
                    /* event.preventDefault();
                var respuesta = confirm('¿Desea cancelar la solicitud?');
                if (respuesta) {
                    
                    $(this).closest('form').submit();
                } else {
                    return false;
                }*/
            });

            $('.cancelar').on('click', function(event) {
               

               Swal.fire({
                   title: 'Estimado colaborador, esta seguro de cancelar la solicitud?',
                   text: "",
                   //icon: 'warning',
                   imageUrl: 'img/logo.png',
                   imageWidth: 400,
                   imageHeight: 200,
                   imageAlt: 'Custom image',
                   showCancelButton: true,
                   confirmButtonColor: '#3085d6',
                   cancelButtonColor: '#d33',
                   confirmButtonText: 'Aceptar!'
                   }).then((result) => {
                   if (result.isConfirmed) {
                       Swal.fire(
                       'La solicitud ha sido Cancelada satisfactoriamente!'
                       
                       )
                       $(this).closest('form').submit();
                   }
                   })
                   /* event.preventDefault();
               var respuesta = confirm('¿Desea cancelar la solicitud?');
               if (respuesta) {
                   
                   $(this).closest('form').submit();
               } else {
                   return false;
               }*/
           });

            $('.denegar').on('click', function(event) {
              //  alert('Solicitud Denegada por Porcentaje de ausentismo en el modulo');
                Swal.fire({
                    title: '',
                    text: "Estimado colaborador, tu solicitud ha sido denegada por porcentaje de ausentismo en el módulo.",
                    imageUrl: 'img/logo.png',
                    imageWidth: 400,
                    imageHeight: 200,
                    imageAlt: 'Custom image',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!'
                }).then((result) => {
                  //  $(this).closest('form').submit();
                    /* if (result.isConfirmed) {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                   }*/
                })
                
               // $(this).closest('form').submit();
              //  window.location.href = 'permisos.update';
                
            });
        });
    </script>
@endsection