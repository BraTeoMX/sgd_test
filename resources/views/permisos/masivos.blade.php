@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Permisos masivos</h3>
            </div>
            <div class="card-body">

                <!--@if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{session('success')}}
                    </div>
                @endif-->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form action="{{ route('importarmasivos') }}" id="form1" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="file">Importar archivo excel</label>
                        <input class="form-control" type="file" name="file"  id="file">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::submit("Actualizar", ["class" => "btn btn-primary mr-2", "id" => "actualizar" ]); !!}
                            <a href="{!! route('home') !!}" class="btn btn-light">Cancelar</a>
                        </div>
                    </div>

                </form>
                {!! BootForm::close() !!}

            </div>
        </div>
    </div>
</div>
@endsection
@section('scriptBFile')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>

<script>

    $( "#actualizar" ).click(function() {
        // alert( "solicitud enviada con exito." );
        Swal.fire('Los pemisos masivos han sido guardados')
    });


</script>

@endsection
