@extends('layouts.main')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-6">
            <h3>Roles</h3>
          </div>
          <div class="col-md-6">
            <a href="{{ route('role.create') }}" class="btn btn-primary pull-right">
              Agregar
            </a>
          </div>
        </div>
      </div>
      <div class="card-body">
          {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
          <div class="row align-items-center col-md-6 col-lg-6">
              {!! BootForm::text("rol", "Nombre del rol:", old("quincena", optional(request())->rol), ["width" => "col-md-5"]); !!}
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
                  <th>Nombre</th>
                  <th data-hide="phone">Eliminar</th>
                </tr>
              </thead>
              <tbody>
                @forelse($roles as $rol)
                <tr>
                  <td>
                    <a href="{{ route('role.edit', $rol) }}" class="btn btn-link">{{ $rol->name}}</a>
                  </td>
                  <td class="eliminar">
                    @destroy(route('role.destroy',$rol))
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="2">
                    <p>No se han registrado roles </p>
                  </td>
                </tr>
                @endforelse
              </tbody>
              <tfoot style="display:{{(count($roles)>25)?"show":"none"}}">
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
