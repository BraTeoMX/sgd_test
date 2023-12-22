@extends('layouts.main')
@section('content')
<div class="row">   
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Permisos por Roles</h3>
            </div>
            <div class="card-body">
                <div class="row"> 
                    <div class="col-12">
                        <table class="table" data-page-size="50" >
                        <thead style="display:{{ ($roles->count()) ? 'show' : 'none' }}">
                            <tr>
                                <th data-sortable="true">Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($roles as $rol)
                        <tr>
                            <td>
                                <a href="{{ route('acceso.show', $rol) }}">
                                {{ $rol->name }} 
                                </a>
                            </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No se ha registrado información en este apartado</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="5">
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