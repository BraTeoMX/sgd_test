@extends('layouts.main')
@section('content')
<div class="row">   
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Actualizar saldo de vacaciones</h3>
            </div>
            <div class="card-body">

                <!--@if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{session('success')}}
                    </div>                    
                @endif-->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('saldovacaciones.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="file">Importar archivo excel</label>
                        <input class="form-control" type="file" name="file"  id="file">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::submit("Actualizar", ["class" => "btn btn-primary mr-2"]); !!}
                            <a href="{!! route('saldovacaciones.index') !!}" class="btn btn-light">Cancelar</a>
                        </div>
                    </div>
                </form>
                {!! BootForm::close() !!}
                <div class="row"> 
                    <div class="col-12">
                        <table class="table" data-page-size="50" >
                        <thead style="display:{{ ($bitacora->count()) ? 'show' : 'none'}}">
                            <tr>
                                <th data-sortable="true">Fecha</th>
                                <th data-sortable="true">Archivo</th>
                                <th data-sortable="true">Total registros</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bitacora as $bit)
                                
                            <tr>
                                <td>
                                    {{$bit->fecha}}
                                </td>
                                <td>
                                    {{$bit->archivo}}
                                </td>
                                <td>
                                    {{$bit->no_registros}}
                                </td>
                            </tr>

                            @empty
                                
                            @endforelse
                        
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                <div>
                                    {{$bitacora->links()}}
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
