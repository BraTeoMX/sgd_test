@extends('layouts.main')
@section('styleBFile')
<!-- Color Box -->
<link href="{{ asset('colorbox/colorbox.css') }}" rel="stylesheet">
<link href="{{ asset('materialfront/assets/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="row">   
    <div class="col-12">
        <div class="card">
            <div class="card-header"> 
                <h3>Calendario</h3>
            </div>
            <div class="card-body">
                {!! BootForm::open(['model' => $calendario, 'store' => 'calendario.store', 'update' => 'calendario.update', 'id'=>'form']) !!}
                    
                    <div class="row">
                        @isset($calendario->id)
                        <div class="col-lg-6 col-md-6">
                            {!! BootForm::text('id', 'ID: ', old('parametro'), ['width'=>'col-md-6','readonly']); !!}
                        </div>
                        @else
                            
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-6">
                            {!! BootForm::text('detalle', 'Descripcion: *', old('descripcion'), ['width'=>'col-md-6']); !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            {!! BootForm::date('fecha_calendario', 'Fecha: *', old('descripcion'), ['width'=>'col-md-6']); !!}
                        </div>  
                   
                        <div class="col-lg-6 col-md-6">
                            <p>Modulo</p>
                            <select class='form-control' aria-label="Default select example" name="modulo" id="modulo">
                                <option value= 0 >{{'--SELECCIONE--'}}</option>
                                <option value= 1 >{{'Vacaciones'}}</option>
                                <option value= 2 >{{'Permisos'}}</option>
                            </select>
                            <br>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-lg-6 col-md-6">
                            <p>Tipo de Nómina</p>
                            <select class='form-control' aria-label="Default select example" name="tipo_nomina" id="tipo_nomina">
                                <option value= 0 >{{'--SELECCIONE--'}}</option>
                                <option value= 1 >{{'Semanal'}}</option>
                                <option value= 2 >{{'Quincenal'}}</option>
                                <option value= 3 >{{'Todas'}}</option>
                            </select>
                            <br>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <p>Tipo de Día</p>
                            <select class='form-control' aria-label="Default select example" name="tipo" id="tipo">
                                <option value= 0 >{{'--SELECCIONE--'}}</option>
                                <option value= 1 >{{'No Laborable'}}</option>
                                <option value= 2 >{{'Reservado'}}</option>
                                <option value= 3 >{{'Bloqueado'}}</option>
                            </select>
                            <br>
                        </div>
                    </div>         
                                    
                    <div class="row">  
                        <div class="col-lg-12 col-md-6">                                      
                            {!! Form::submit("Guardar", ["class" => "btn btn-success mr-2"]); !!}
                            <a href="{!! route('calendario.index') !!}" class="btn btn-light">Cancelar</a>
                        </div>
                    </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
</div> 
@endsection
@section('scriptBFile')
<script type="text/javascript" src="{{ asset('colorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('materialfront/assets/vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('materialfront/assets/vendor/select2/dist/js/select2.js') }}"></script>
<script>
    var attributeValues = [];
    $(document).ready(function() {
         
             
        $('.js-select2-custom').each(function () {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });
    });


</script>
@endsection