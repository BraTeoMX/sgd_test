@extends('layouts.main')
@section('content')
<div class="card">
  <div class="card-header">
    <h3>Responsables</h3>
  </div>
  <div class="card-body">
        {!! BootForm::open(['model' => $responsables, 'store' => 'responsables.store', 'update' => 'responsables.update','id'=>'form']) !!}
        <div class="form-group">
          <select class='form-control' aria-label="Default select example" name="name" id="name">
              @forelse ($puestos as $puesto)
                <option value={{$puesto->id}}>{{$puesto->Puesto}}</option>
                
              @empty
              @endforelse
           </select>
        </div>
        
        <div class="row">
          <div class="col-md-12 text-left">
            <button type="submit" name="enviar" class="btn btn-primary">Guardar</button>
            <a href="{!! route('responsables.index') !!}" class="text-danger text-right col-md-2">Cancelar</a>
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
