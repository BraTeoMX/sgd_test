@extends('layouts.main')
@section('content')
<div class="card">
  <div class="card-header">
        <h3>Usuarios</h3>
        <a href="{{ route('usuario.create') }}" class="btn btn-primary pull-right">
          Agregar
        </a>
  </div>
  <div class="card-body">
    {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
    <div class="row align-items-center">
        <div class="col-md-3">
            {!! BootForm::text('name', 'Nombre:', old('name', optional(request())->name), ['maxlength' => '50']); !!}
        </div>
        <div class="col-md-3">
            {!! BootForm::text('email', 'Correo electrónico:', old('email', optional(request())->email), ['maxlength' => '50',]); !!}
        </div>
        <div class="col-md-3">
            {!! BootForm::select('estatus','Estatus:' , ['Todas'=>'Todas', 'Activas'=>'Activas', 'Inactivas'=>'Inactivas'],
            old('inactivo', (request()->inactivo!="")?request()->inactivo:'Todas'),['width'=>'col-md-6']) !!}
        </div>
        <div class="col-md-3">
            {!! Form::submit('Buscar', ['class' => 'btn btn-primary']); !!}
        </div>
    </div>
    {!! BootForm::close() !!}
   <div class="row">
      <div class="col-md-12">
          <div class="table-responsive">
              <table class="table toggle-circle">
                  <thead style="display:{{(count($usuarios)) ? "show" : "none"}}">
                      <tr>
                          <th>Nombre</th>
                          <th>No. de Empleado</th>
                          <th>Rol</th>
                          <th>Correo electrónico</th>
                          <th>Estatus</th>
                        <!-- <th data-hide="phone">Último acceso</th> -->
                          <th data-hide="phone">Reenvio Email</th>
                      </tr>
                  </thead>
                  <tbody>
                      @forelse($usuarios as $usuario)
                          <tr>
                              <td>
                                  {!! ($usuario->inactivo == 'X') ? '<i class="legend-indicator bg-danger"></i>' : '<i class=" legend-indicator bg-success"></i>' !!}
                                  @if ($usuario->no_empleado != '1' && $usuario->no_empleado != '2' )
                                  <a href="{{ route('usuario.edit', $usuario) }}" title="Editar" class="btn-link">
                                  @endif  
                                      {{ $usuario->name }}
                                    
                                  </a>
                              </td>
                              <td>{{ $usuario->no_empleado }}</td>
                              <td>{{ implode(", ",$usuario->getRoleNames()->toArray()) }}</td>
                              <td>{{ $usuario->email }}</td>
                              <td>{{ ($usuario->inactivo=='X')?'Inactivo':'Activo' }}</td>
                            <!--  <td>
                                  {!! optional($usuario->fecha_ultimo_acceso)->format('d/m/Y'). '<br>' . optional($usuario->fecha_ultimo_acceso)->format('H:i:s')!!}
                              </td>-->
                              <td class="text-center">
                                  <a href="{{ route('usuario.notificacion', $usuario) }}" class="btn btn-link">
                                      <i class="tio-email"></i>
                                  </a><br>
                                  <small>{{optional($usuario->fecha_ultima_notificacion)->format('d/m/Y')}}</small>
                              </td>
                          </tr>
                      @empty
                          <tr>
                              <td colspan="4">No se ha registrado este usuario</td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scriptBFile')
@endsection
