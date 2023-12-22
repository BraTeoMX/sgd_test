<!-- Bootstrap Core CSS -->
<!-- <link href="{{ asset('materialpro/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('materialpro/css/style.css') }}" rel="stylesheet"> -->
<!-- You can change the theme colors from here -->
<!-- <link href="{{ asset('materialpro/css/colors/blue.css') }}" id="theme" rel="stylesheet">
<link href="{{ asset('materialpro/assets/plugins/footable/css/footable.core.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('materialpro/assets/plugins/footable/js/footable.all.min.js') }}"></script> -->

<div class="card blog-widget">
    <div class="card-header">
        <h3>Codigos Postales</h3>
    </div>
    <div class="card-body b-t">
    {!! BootForm::open(['model' => $codigospostales, 'id'=>'form']); !!}
        <div class="row">
            {!! BootForm::text('codigo_postal', ['html'=>'Código postal:<span class="text text-danger">*</span>'], old('codigo_postal', $strCodigoPostal), ['width' => 'col-md-12']); !!}
        </div>

        <div class="row">
            {!! BootForm::submit('Enviar', ['class' => 'btn btn-primary']); !!}
        </div>
    {!! BootForm::close() !!}


      <div class="row">
          <div class="col-md-12">
              <h2>Para seleccionar un valor haz clic en el campo código postal</h2>
          </div>
      </div>
      <div class="row">
          <div class="col-md-12">
              <table class="table toggle-circle tabla-foo">
                  <thead>
                      <tr>
                          <th>Código postal</th>
                          <th>Localidad</th>
                          <th>Estado</th>
                          <th data-hide="phone">municipio</th>
                      </tr>
                  </thead>
                  <tbody>
                      @forelse ($codigospostales as $codigopostal)
                          {!! BootForm::open(['id'=>'form-'.$codigopostal->id]) !!}
                          {!! "<tr>" !!}
                          {!! "<td>".
                              BootForm::hidden('codigo_postal', $codigopostal->codigo_postal, ['id'=>'codigo_postal-form-'.$codigopostal->id]).

                              $codigopostal->codigo_postal.
                          "</td>" !!}
                          {!! "<td>".
                              BootForm::hidden('localidad_id', $codigopostal->id, ['id'=>'localidad_id-form-'.$codigopostal->id]).
                              BootForm::hidden('localidad', $codigopostal->localidad, ['id'=>'localidad-form-'.$codigopostal->id]).
                            '<a href="#" data="form-'.$codigopostal->id.'">'.  $codigopostal->localidad.'</a>'.
                          "</td>" !!}
                          {!! "<td>".
                              BootForm::hidden('estado_id', $codigopostal->estado_id, ['id'=>'estado_id-form-'.$codigopostal->id]).
                              BootForm::hidden('estado', $codigopostal->estados->estado, ['id'=>'estado-form-'.$codigopostal->id]).
                              $codigopostal->estados->estado.
                          "</td>" !!}
                          {!! "<td>".
                              BootForm::hidden('municipio_id', $codigopostal->municipio_id, ['id'=>'municipio_id-form-'.$codigopostal->id]).
                              BootForm::hidden('municipio', $codigopostal->nombre_municipio, ['id'=>'municipio-form-'.$codigopostal->id]).
                              $codigopostal->nombre_municipio.
                          "</td>" !!}
                          {!! "</tr>" !!}
                          {!! BootForm::close() !!}
                      @empty
                          <tr>
                              <td colspan="3">Sin resultados</td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
      </div>

  </div>
</div>

<script >
$( document ).ready(function() {
  $('.tabla-foo').footable();
  $('a').click(function(event) {
      event.preventDefault();
      var idForm = $(this).attr('data');
      var valores = parent.attributeValues;
      for(var i=0; i < valores.length; i++){
          parent.$(valores[i][0]).val($(valores[i][1]+'-'+idForm).val());
      }
      parent.$.fn.colorbox.close();
  });
});
</script>
<!-- Laravel Javascript Validation -->
<!-- <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! $validator !!} -->
