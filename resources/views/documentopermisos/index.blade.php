@extends('layouts.main')
@section('content')
<div class="row">   
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Actualizar Documento Permisos</h3>
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
                               
                <form action="{{ route('documentopermisos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                 
                        <label class="form-label" for="file">Importar archivo </label>
                        <input class="form-control" type="file" name="file"  id="file">
                        {!! BootForm::hidden('no_aux', $folio); !!}
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::submit("Actualizar", ["class" => "btn btn-primary mr-2"]); !!}
                            <a href="{!! route('estatuspermisos.index') !!}" class="btn btn-light">Cancelar</a>
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
@endsection
