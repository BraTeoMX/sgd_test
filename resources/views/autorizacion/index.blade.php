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
        <h3>Matriz de Permisos</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 scrollmenu">
                {!! BootForm::open(['id' => 'form', 'class' => '','method'=>'POST','route' => 'autorizacion.index']) !!}
                    @if($permisos->isNotEmpty())
                        <table class="table toggle-circle tabla-foot" data-page-size="25">
                            <thead>
                                <tr>
                                    <th>Permisos</th>
                                    @foreach($valorpermisos as $valor)
                                    <th>{{$valor->valorpermiso}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permisos as $permiso)
                                <tr>
                                    <td>{{$permiso->permiso}}
                                    </td>
                                    @foreach($valorpermisos as $valor)
                                    <td>
                                      @php
                                        $valores = $valor->cve_valorpermiso;
                                      @endphp

                                      @if($permiso->$valores==1)
                                          {!! Form::checkbox($permiso->id_permiso.'['.$valor->cve_valorpermiso.']',1,old('check','1') , ['id' => 'check'.$permiso->id_permiso.'-'.$valor->id_valorpermiso, 'class' => 'i-checks','width'=> 'col-lg-2']); !!}
                                      @else
                                      {!! Form::checkbox($permiso->id_permiso.'['.$valor->cve_valorpermiso.']',1,old('check','') , ['id' => 'check'.$permiso->id_permiso.'-'.$valor->id_valorpermiso, 'class' => 'i-checks','width'=> 'col-lg-2']); !!}
                                      @endif    
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="display:{{(count($permisos)>50)?"show":"none"}}">
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
                @if($valorpermisos->isEmpty())
                    <p>No se han registrado permisos </p>
                @endif
                {{ BootForm::close() }}
            </div>
        </div>

@endsection
@section('scriptBFile')
@endsection
