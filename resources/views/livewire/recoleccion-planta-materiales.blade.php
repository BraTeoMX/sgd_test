<div class="card">
    <div class="card-header">
        <h4>Recolección en planta</h4>
    </div>

    <div class="card-body">
        <table class="table" id="products_table">
            <thead>
            <tr>
                <th></th>
                <th>Material</th>
                <th>Sucursal</th>
                <th>Sitio/Lugar</th>
                <th>Folio</th>
                <th>Peso tara</th>
                <th>Peso bruto</th>
                <th>Total</th>
                <th>Recoleccion realizada</th>
                <th>Llegada transporte</th>
                <th>Salida tranporte</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orderProducts as $index => $orderProduct)
                <tr>
                    <td>
                        {!! BootForm::number("orderProducts[".$index."][id]", false, old("orderProducts[".$index."][id]"), ["wire:model"=>"orderProducts.".$index.".id", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][material_id]", false, $allMateriales, old("orderProducts[".$index."][material_id]"), ["wire:model"=>"orderProducts.".$index.".material_id", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][sucursal_id]", false, $allSucursales, old("orderProducts[".$index."][sucursal_id]"), ["wire:model"=>"orderProducts.".$index.".sucursal_id", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][sitio_id]", false, $allClienteSitios, old("orderProducts[".$index."][sitio_id]"), ["wire:model"=>"orderProducts.".$index.".sitio_id", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::number("orderProducts[".$index."][folio_planta]", false, old("orderProducts[".$index."][folio_planta]"), ["wire:model"=>"orderProducts.".$index.".folio_planta", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::number("orderProducts[".$index."][peso_tara_planta]", false, old("orderProducts[".$index."][peso_tara_planta]"), ["wire:model"=>"orderProducts.".$index.".peso_tara_planta", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::number("orderProducts[".$index."][peso_bruto_planta]", false, old("orderProducts[".$index."][peso_bruto_planta]"), ["wire:model"=>"orderProducts.".$index.".peso_bruto_planta", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::number("orderProducts[".$index."][total_planta]", false, old("orderProducts[".$index."][total_planta]"), ["wire:model"=>"orderProducts.".$index.".total_planta", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! Bootform::checkboxElement("orderProducts[".$index."][recoleccion_asitencia]",false,'SI',old("orderProducts[".$index."][recoleccion_asitencia]"),false, ["class"=>'customSwitchDefaultSize'] ) !!}

                    </td>
                    <td>
                        {!! BootForm::time("orderProducts[".$index."][hora_llegada_planta]", false,old("orderProducts[".$index."][hora_llegada_planta]"), ["wire:model"=>"orderProducts.".$index.".hora_llegada_planta", "required"=>true]) !!}
                    </td>
                    <td>
                        {!! BootForm::time("orderProducts[".$index."][hora_salida_planta]", false,old("orderProducts[".$index."][hora_salida_planta]"), ["wire:model"=>"orderProducts.".$index.".hora_salida_planta", "required"=>true]) !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

