@extends('layouts.main')
@section('content')
<div class="row">   
    <div class="col-12">
        <div class="card">
            <div class="card-header"> 
                <h3>Calendario</h3>  
                @if(auth()->user()->hasPermissionTo("calendario.create"))
                <a href="{{ route('calendario.create')}} " class="btn btn-primary float-right">
                    Agregar
                </a>
                @endif
            </div>
            <div class="card-body">
                {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
                <div class="form-group">   
                    <div class="col-8 input-group">
                        <label class="text-dark" style="line-height: 2;"> Fecha de reservacion :&nbsp;</label>
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
                        <thead style="display:{{ ($fechas->count()) ? 'show' : 'none' }}">
                            <tr>
                                <th data-sortable="true">Clave</th>
                                <th data-sortable="true">fecha</th>
                                <th data-sortable="true">Modulo</th>
                                <th data-sortable="true">Estatus</th>
                                <th data-sortable="true">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fechas as $fech)
                            <tr>
                                <td>
                                    <a href=" {{ route('calendario.edit', $fech->id)}} ">
                                        {{$fech->id}}
                                    </a>
                                </td>
                                <td>
                                    {{$fech->fecha_calendario}}
                                </td>
                                <td>
                                    @if ($fech->id_modulo == 1)
                                        {{'VACACIONES'}}                                        
                                    @endif
                                </td>
                                <td>
                                    {{$fech->detalle}}
                                </td>
                                @if(auth()->user()->hasPermissionTo("calendario.destroy"))
                                    <td class="float-center">
                                        {!! Form::model($fech, ['method' => 'delete', 'route' => ['calendario.destroy',$fech->id] ]) !!}
                                        <a class="text-danger eliminar" style="cursor: pointer" onclick="">
                                            <i class="tio-delete tio-lg text-danger"></i>
                                        </a>
                                        {!! Form::close() !!}
                                    </td> 
                                @endif
                            </tr>
                                
                            @empty
                                
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