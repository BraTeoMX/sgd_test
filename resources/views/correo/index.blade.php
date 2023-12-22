@extends('layouts.main')
@section('content')
<div class="row">   
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Correos</h3>
            </div>
            <div class="card-body">
                

                <form action="{{ route('correo.store') }}" method="POST" >
                @csrf
                <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                   
                                    <div class="form-group">
                                        <label for="puerto">Correo del Usuario </label>
                                        <input type="text" name="user" value="{{env('MAIL_USERNAME')}}" class="form-control" placeholder="Ingrese correo">
                                    </div>
                                    <div class="form-group">
                                        <label for="puerto">Contraseña </label>
                                        <input type="text" name="password" value="{{env('MAIL_PASSWORD')}}" class="form-control" placeholder="Ingrese contraseña">
                                    </div>
                                    <div class="form-group">
                                        <label for="host">Host de correo electronico</label>
                                        <input type="text" name="host" value="{{env('MAIL_HOST')}}" class="form-control" placeholder="Ingrese host">
                                    </div>
                                    <div class="form-group">
                                        <label for="puerto">Puerto de correo electronico</label>
                                       <input type="text" name="port" value="{{env('MAIL_PORT')}}" class="form-control" placeholder="Ingrese puerto">
                                    </div>
                                    <div class="form-group">
                                        <label for="encryption">Encriptacion </label>
                                        {!! Form::select('encryption', array('tls','ssl'),env('MAIL_ENCRYPTION'),['class' => 'form-control']); !!}
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
<script>

</script>
@endsection