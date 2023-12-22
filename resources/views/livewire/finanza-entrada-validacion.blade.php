<div class="card">
    <div class="card-header">
        <h4>validación finanzas</h4>
    </div>

    <div class="table-responsive">
    <table id="basicDatatable" class="table table-text-center table-input-group table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
            data-hs-datatables-options='{
                        "order": []
                    }'>
            <thead class="thead-light">
            <tr>
                <th></th>
                <th>Sucursal</th>
                <th>Material</th>
                <th>U. Medida</th>
                <th>Precio</th>
                <th>F Intimark</th>
                <th>F planta</th>
                <th>Tara Planta</th>
                <th>Bruto  Planta</th>
                <th>Total Planta</th>
                <th>Tara Bodega</th>
                <th>Bruto  Bodega</th>
                <th>Total Bodega</th>
                <th>Tara Validación</th>
                <th>Bruto  Validación</th>
                <th>Total Validación</th>
                <th>Estatus</th>
                <th>Observaciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orderProducts as $index => $orderProduct)
                
                <tr>
                    <td>
                        {!! BootForm::hidden("orderProducts[".$index."][id]",$orderProducts[$index]['id']) !!}
                        {!! BootForm::hidden("orderProducts[".$index."][material_id]",$orderProducts[$index]['material_id']) !!}
                        {!! BootForm::hidden("orderProducts[".$index."][sucursal_id]",$orderProducts[$index]['sucursal_id']) !!}
                        {!! BootForm::hidden("orderProducts[".$index."][bodega_id]",$orderProducts[$index]['bodega_id']) !!}
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['sucursal']}}</label>                   
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['material']}}</label>                   
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['unidad_medida']}}</label>   
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['precio_pagar']}}</label>   
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['folio_Intimark']}}</label>  
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['folio_planta']}}</label>  
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['peso_tara_planta']}}</label>  
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['peso_bruto_planta']}}</label>  
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['total_planta']}}</label>  
                    </td>   
                    <td>
                        <label> {{$orderProducts[$index]['peso_tara_bodega']}}</label>  
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['peso_bruto_bodega']}}</label>  
                    </td>
                    <td>
                        <label> {{$orderProducts[$index]['total_bodega']}}</label>  
                    </td>
                    <td>
                        {!! BootForm::number("orderProducts[".$index."][peso_tara_validacion]", false, old("orderProducts[".$index."][peso_tara_validacion]"), ["wire:model"=>"orderProducts.".$index.".peso_tara_validacion", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::number("orderProducts[".$index."][peso_bruto_validacion]", false, old("orderProducts[".$index."][peso_bruto_validacion]"), ["wire:model"=>"orderProducts.".$index.".peso_bruto_validacion", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::number("orderProducts[".$index."][total_validacion]", false, old("orderProducts[".$index."][total_validacion]"), ["wire:model"=>"orderProducts.".$index.".total_validacion", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][estatus_detalle_id]", false, $allEstatus, old("orderProducts[".$index."][estatus_detalle_id]"), ["wire:model"=>"orderProducts.".$index.".estatus_detalle_id", "required"=>true]); !!}
                    </td>

                    <td>
                        {!! BootForm::textarea("orderProducts[".$index."][observaciones_validacion]", false, old("orderProducts[".$index."][observaciones_validacion]"), ["rows" => "2", "style" => "rezise:true"], ["wire:model"=>"orderProducts.".$index.".observaciones_validacion", "required"=>true]); !!}
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

