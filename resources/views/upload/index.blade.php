@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
              <div class="card-header">
                    <h3>Archivos Adjuntos</h3>
                    <a href="{{ route('upload.create') }}" class="btn btn-primary float-right">
                      Agregar Archivo
                    </a>
              </div>
              <div class="card-body">
                {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
                <div class="row align-items-center">
                    <div class="col-md-4">
                        {!! BootForm::text('folio_cliente', 'Folio:', old('folio_Intimark', optional(request())->folio_Intimark), ['maxlength' => '50']); !!}
                    </div>
                    <div class="col-md-3">
                        {!! Form::submit('Buscar', ['class' => 'btn btn-light']); !!}
                    </div>
                </div>
                {!! BootForm::close() !!}
               <div class="row">
                  <div class="col-md-12">
                      <div class="table-responsive">
                          <table class="table toggle-circle">
                              <thead style="display:{{(count($entradas)) ? "show" : "none"}}">
                                  <tr>
                                      <th>Descripcion</th>
                                      <th>Eliminar</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @forelse($entradas as $entrada)
                                      <tr>
                                          <td>
                                              <a class="btn-link" ">
                                                  {{ $entrada->descripcion }}
                                              </a>
                                          </td>
                                          <!-- storage/app/public/storage/public/1603952004.jpeg -->
                                          @php
                                            $url = (!empty($entrada->nombre_archivo)) ? asset("storage/public/$entrada->nombre_archivo") : "#";
                                                $archivo = '<div id="divarchivo" >'
                                                .'<a id="nombre_archivo-link" href="'.$url.'" target="_blank">Ver Archivo</a>'
                                                .'</div>';
                                            @endphp
                                          <td>
                                          {!! $archivo !!}
                                          </td>

                                      </tr>
                                  @empty
                                      <tr>
                                          <td colspan="3">No se ha registrado información en este apartado</td>
                                      </tr>
                                  @endforelse
                              </tbody>
                          </table>
                      </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptBFile')
@endsection
