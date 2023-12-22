@extends('layouts.main')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-6">
            <h3>Permisos</h3>
          </div>
          <div class="col-md-6">
            <a href="{{ route('permiso.create') }}" class="btn btn-primary pull-right">
              Agregar
            </a>
          </div>
        </div>
      </div>
     <div class="card-body">
         {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
         <div class="row align-items-center col-md-6">
             {!! BootForm::text("permiso", "Nombre del permiso:", old("quincena", optional(request())->permiso), ["width" => "col-md-5"]); !!}
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
                  @forelse($permisos as $permiso)
                      <tr>
                          <td>
                              <a href="{{ route('permiso.edit', $permiso) }}" class="btn btn-link">{{ $permiso->name}}</a>
                          </td>
                          <td class="eliminar">
                            @destroy(route('permiso.destroy',$permiso))
                          </td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="2">No se ha registrado este usuario</td>
                      </tr>
                  @endforelse
              </tbody>
              <tfoot style="display:{{(count($permisos)>25)?"show":"none"}}">
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
