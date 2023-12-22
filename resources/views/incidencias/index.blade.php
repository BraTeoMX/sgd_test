@extends('layouts.main')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-7">
            <h3>incidencias</h3>
          </div>
          <div class="col-md-5">
            <a href="{{ route('incidencia.create') }}" class="btn btn-primary pull-right">
              Agregar
            </a>
          </div>
        </div>
      </div>
     <div class="card-body">
         {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
         <div class="row align-items-center col-md-6">
             {!! BootForm::text("incidencia", "Nombre del incidencia:", old("quincena", optional(request())->incidencia), ["width" => "col-md-5"]); !!}
             <div class="col-md-2">
                 {!! Form::submit('Buscar', ['class' => 'btn btn-primary']); !!}
             </div>
         </div>
         {!! BootForm::close() !!}
            <table class="table toggle-circle tabla-foo" data-page-size="25">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th data-hide="phone">Eliminar</th>
                </tr>
              </thead>
              <tbody>
                  @forelse($incidencias as $incidencia)
                      <tr>
                          <td>
                              <a href="{{ route('incidencia.edit', $incidencia) }}" class="btn btn-link">{{ $incidencia->name}}</a>
                          </td>
                          <td class="eliminar">
                            @destroy(route('incidencia.destroy',$incidencia))
                          </td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="2">No se ha registrado infformacion</td>
                      </tr>
                  @endforelse
              </tbody>
              <tfoot style="display:{{(count($incidencias)>25)?"show":"none"}}">
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
@endsection
@section('scriptBFile')
@endsection
