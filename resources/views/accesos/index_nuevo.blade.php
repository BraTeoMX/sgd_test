@extends('layouts.main')
@section('content')
    <style media="screen">
    div.scrollmenu {
      background-color: #fff;
      overflow: auto;
      white-space: nowrap;
    }

    div.scrollmenu a {
      display: inline-block;
      color: white;
      text-align: center;
      padding: 14px;
      text-decoration: none;
    }

    div.scrollmenu a:hover {
      background-color: #777;
    }
    </style>
    <div class="card">
        <div class="card-header bg-light">
        <h3>Accesos</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 scrollmenu">
                {!! BootForm::open(['id' => 'form', 'class' => '','method'=>'POST','route' => 'acceso.index']) !!}
                    @if($permisos->isNotEmpty())
                        <table class="table toggle-circle tabla-foot" data-page-size="25">
                        {!! BootForm::hidden('rol',$rol[0]->id) !!}
                            <thead>
                                <tr>
                                    <th>Permiso</th>                                   
                                    <th>{{$rol[0]->name}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permisos as $permiso)
                                <tr>
                                    <td>{{$permiso->name}}
                                    </td>
                                    <td>
                                        {!! Form::checkbox($rol[0]->name.'['.$permiso->name.']',$permiso->name,
                                        old('check',$rol[0]->hasPermissionTo($permiso)) , ['id' => 'check'.$rol[0]->id.'-'.$permiso->id, 
                                        'class' => 'i-checks','width'=> 'col-lg-2']); !!}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="display:{{(count($permisos)>25)?"show":"none"}}">
                              <tr>
                                <td colspan="4">
                                  <div>
                                    <ul class="pagination"> </ul>
                                  </div>
                                </td>
                              </tr>
                            </tfoot>
                        </table>
                      </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-left">
                    <button type="submit" name="enviar" class="btn btn-primary">Guardar</button>
                    <a href="{!! url('/acceso') !!}" class="text text-danger btn-link float-middle ml-4">Cancelar</a>
                    </div>
                </div>
                @endif
                @if($permisos->isEmpty())
                    <p>No se han registrado roles o permisos </p>
                @endif
                {{ BootForm::close() }}
            </div>
        </div>

@endsection
@section('scriptBFile')
@endsection
