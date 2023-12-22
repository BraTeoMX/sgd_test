@extends('layouts.main')
@section('content')
<div class="row">   
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Base de Datos</h3>
            </div>
            <div class="card-body">
                

                <form action="{{ route('base.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="host">Host</label>
                                        <input type="text" name="host" value="{{env('DB_HOST')}}" class="form-control"
                                            placeholder="Ingrese host">
                                    </div>
                                    <div class="form-group">
                                        <label for="puerto">Puerto</label>
                                        <input type="text" name="port" value="{{env('DB_PORT')}}" class="form-control"
                                            placeholder="Ingrese puerto">
                                    </div>
                                    <div class="form-group">
                                        <label for="bd">Base de datos</label>
                                        <input type="text" name="bd" value="{{env('DB_DATABASE')}}"
                                            class="form-control" placeholder="Ingrese nombre de su base de datos">
                                    </div>
                                    <div class="form-group">
                                        <label for="user">Usuario </label>
                                        <input type="text" name="user" value="{{env('DB_USERNAME')}}"
                                            class="form-control" placeholder="Ingrese contraseña">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Contraseña</label>
                                        <input type="text" name="password" value=" {{env('DB_PASSWORD')}}" class="form-control" placeholder="Ingrese contraseña">
                                    </div>

                                    <br>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
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