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
                <h3>Puestos</h3>
            </div>
            <div class="card-body">
                {!! BootForm::open(['model' => $puesto, 'store' => 'puestos.store', 'update' => 'puestos.update', 'id'=>'form']) !!}
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            {!! BootForm::text('Puesto', 'Puesto: ', old('Puesto'), ['width'=>'col-md-6']); !!}
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            {!! BootForm::text('Id_Planta', 'Planta: *', old('Id_Planta'), ['width'=>'col-md-6']); !!}
                        </div>  
                        <div class="col-lg-6 col-md-6">
                        {!! BootForm::text('Nivel', 'Nivel: *', old('Nivel'), ['width'=>'col-md-6']); !!}
                        </div>
                    </div> 
                    
                    <div class="row">  
                        <div class="col-md-12">                                      
                            {!! Form::submit("Guardar", ["class" => "btn btn-success mr-2"]); !!}
                            <a href="{!! route('puestos.index') !!}" class="btn btn-light">Cancelar</a>
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