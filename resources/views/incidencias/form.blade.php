@extends('layouts.main')
@section('content')
<div class="card">
  <div class="card-header">
    <h3>incidencia</h3>
  </div>
  <div class="card-body">
        {!! BootForm::open(['model' => $incidencia, 'store' => 'incidencia.store', 'update' => 'incidencia.update','id'=>'form']) !!}
        <div class="form-group">
          {!! BootForm::text("name", "Nombre del incidencia:", old("name",$incidencia->name), ["class" => "form-control",'width'=>'col-lg-3 col-md-4','maxlength' => '40']); !!}
        </div>
        <div class="row">
          <div class="col-md-12">
            <button type="submit" name="enviar" class="btn btn-primary">Guardar</button>
            <a href="{!! route('incidencia.index') !!}" class="text-danger text-right col-md-2">Cancelar</a>
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
