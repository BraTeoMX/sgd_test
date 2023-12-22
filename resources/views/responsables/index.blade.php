@extends('layouts.main')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-7">
            <h3>Responsables</h3>
          </div>
          <div class="col-md-6">
            <a href="{{ route('responsables.create') }}" class="btn btn-primary pull-right">
              Agregar
            </a>
          </div>
        </div>
      </div>
      <div class="card-body">
          {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
          <div class="row align-items-center col-md-6 col-lg-6">
              {!! BootForm::text("responsable", "Puesto Responsable:", old("quincena", optional(request())->name), ["width" => "col-md-5"]); !!}
              <div class="col-md-2">
                  {!! Form::submit('Buscar', ['class' => 'btn btn-primary']); !!}
              </div>
          </div>
          {!! BootForm::close() !!}
        <div class="row">
          <div class="col-md-12">
            <table class="table toggle-circle tabla-foo" data-page-size="10">
              <thead>
                <tr>
                  <th>Puesto Responsable</th>
                  <th data-hide="phone">Eliminar</th>
                </tr>
              </thead>
              <tbody>
                @forelse($responsables as $responsable)
                <tr>
                  <td>
                    <a href="{{ route('responsables.edit', $responsable) }}" class="btn btn-link">{{ $responsable->guard_name}}</a>
                  </td>
                  <td class="eliminar">
                    @destroy(route('responsables.destroy',$responsable))
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="2">
                    <p>No se han registrado responsables </p>
                  </td>
                </tr>
                @endforelse
              </tbody>
              <tfoot style="display:{{(count($responsables)>25)?"show":"none"}}">
                <tr>
                  <td colspan="2">
                    <div>
                      <ul class="pagination"> </ul>
                    </div>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@section('scriptBFile')
@endsection
