@extends('layouts.main')
@section('content')
<div class="row">   
    <div class="col-12">
        <div class="card">
            <div class="card-header"> 
                <h3>Parametros</h3>  
                @if(auth()->user()->hasPermissionTo("parametros.create"))
                <a href="{{ route('parametros.create')}} " class="btn btn-primary float-right">
                    Agregar
                </a>
                @endif
            </div>
            <div class="card-body">
                {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
                <div class="form-group">   
                    <div class="col-8 input-group">
                        <label class="text-dark" style="line-height: 2;"> Parametro :&nbsp;</label>
                        <input type="search" class="form-control col-8" id="parametros" name="parametros">
                        <span class="col-2">
                        {!! Form::submit('Buscar', ['class' => 'btn btn-light']); !!}                            
                        </span>
                    </div>                    
                </div> 
                {!! BootForm::close() !!}
                <div class="row"> 
                    <div class="col-12">
                        <table class="table" data-page-size="50" >
                        <thead style="display:{{ ($parametros->count()) ? 'show' : 'none' }}">
                            <tr>
                                <th data-sortable="true">Clave</th>
                                <th data-sortable="true">Nombre</th>
                                <th data-sortable="true">Descripcion</th>
                                <th data-sortable="true">Modulo</th>
                                <th data-sortable="true">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($parametros as $parametro)
                        <tr>
                        <td>
                                {{ $parametro->clave }}
                              </td>
                            <td>
                                <a href="{{ route('parametros.edit', $parametro->id) }}">
                                {{ $parametro->parametro }} 
                                </a>
                            </td>
                            <td>
                                {{ $parametro->descripcion }}
                              </td>
                              <td>
                                {{ $parametro->modulo }}
                              </td>
                            <td>
                                {{ $parametro->valor }}
                              </td>
                           
                            @if(auth()->user()->hasPermissionTo("parametros.destroy"))
                            <td class="float-center">
                                {!! Form::model($parametro, ['method' => 'delete', 'route' => ['parametros.destroy',$parametro->id] ]) !!}
                                <a class="text-danger eliminar" style="cursor: pointer" onclick="">
                                    <i class="tio-delete tio-lg text-danger"></i>
                                </a>
                                {!! Form::close() !!}
                            </td> 
                            @endif                           
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No se ha registrado información</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="7">
                            <div>
                                <ul class="pagination"></ul>
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
<script>
        $(document).ready(function() {
            $('.eliminar').on('click', function(event) {
                event.preventDefault();
                var respuesta = confirm('¿Desea eliminar el registro?');
                if (respuesta) {
                    $(this).closest('form').submit();
                } else {
                    return false;
                }
            });
        });
    </script>
    @endsection