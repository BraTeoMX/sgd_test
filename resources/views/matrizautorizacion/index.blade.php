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
        <h3>Matriz de Autorizacion</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 scrollmenu">
                {!! BootForm::open(['id' => 'form', 'class' => '','method'=>'POST','route' => 'autorizacion.index']) !!}
                    @if($responsables->isNotEmpty())
                        <table class="table toggle-circle tabla-foot" data-page-size="25">
                            <thead>
                                <tr>
                                    <th>Responsables</th>
                                    @foreach($incidencias as $incidencia)
                                    <th>{{$incidencia->name}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($responsables as $responsable)
                                <tr>
                                    <td>{{$responsable->Puesto}}
                                    </td>
                                    @foreach($incidencias as $incidencia)
                                    <td>
                                    {!! Form::checkbox($responsable->Puesto.'['.$incidencia->name.']',$incidencia->name,old('check','') , ['id' => 'check'.$responsable->id_puesto.'-'.$incidencia->id, 'class' => 'i-checks','width'=> 'col-lg-2']); !!}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="display:{{(count($responsables)>25)?"show":"none"}}">
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
                    <a href="{!! url('/home') !!}" class="text text-danger btn-link float-middle ml-4">Cancelar</a>
                    </div>
                </div>
                @endif
                @if($incidencias->isEmpty())
                    <p>No se han registrado incidencias o responsables </p>
                @endif
                {{ BootForm::close() }}
            </div>
        </div>

@endsection
@section('scriptBFile')
@endsection
