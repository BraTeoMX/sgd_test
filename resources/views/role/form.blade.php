@extends('layouts.main')
@section('content')
<div class="card">
  <div class="card-header">
    <h3>Registro de rol</h3>
  </div>
  <div class="card-body">
        {!! BootForm::open(['model' => $rol, 'store' => 'role.store', 'update' => 'role.update','id'=>'form']) !!}
        <div class="form-group">
          {!! BootForm::text("name", "Nombre del rol:", old("name",$rol->name), ["class" => "form-control",'width'=>'col-lg-2 col-md-4','placeholder','maxlength' => '30']); !!}
        </div>
        <div class="row">
          <div class="col-md-12 text-left">
            <button type="submit" name="enviar" class="btn btn-primary">Guardar</button>
            <a href="{!! route('role.index') !!}" class="text-danger text-right col-md-2">Cancelar</a>
          </div>
        </div>
        {!! BootForm::close() !!}
  </div>
</div>
@endsection
@section('scriptBFile')
  <script src="{{ asset('js/locales/es.js') }}"></script>
  <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! $validator !!}
@endsection
