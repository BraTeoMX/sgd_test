<div class="card">
    <div class="card-header">
        <h4>Materiales</h4>
    </div>

    <div class="card-body">
        <table class="table" id="products_table">
            <thead>
            <tr>
                <th>Folio Intimark</th>
                <th>Material</th>
                <th>Tipo material</th>
                <th>Peso en planta</th>
                <th>Peso Bruto</th>
                <th>Peso tara</th>
                <th>Peso neto</th>
                <th>Bodega</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orderProducts as $index => $orderProduct)
                <tr>
                    <td>
                        {!! BootForm::text("orderProducts[".$index."][folio_Intimark]", false, old("orderProducts[".$index."][folio_Intimark]"), ["wire:model" => "orderProducts.".$index.".folio_Intimark", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][material_id]", false, $allMateriales, old("orderProducts[".$index."][material_id]"), ["wire:model"=>"orderProducts.".$index.".material_id", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][tipo_material_id]", false, $allTipoMaterial, old("orderProducts[".$index."][tipo_material_id]"), ["wire:model"=>"orderProducts.".$index.".tipo_material_id", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::text("orderProducts[".$index."][peso_cliente]", false, old("orderProducts[".$index."][peso_cliente]"), ["wire:model" => "orderProducts.".$index.".peso_cliente", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::text("orderProducts[".$index."][Intimark_peso_bruto]", false, old("orderProducts[".$index."][Intimark_peso_bruto]"), ["wire:model" => "orderProducts.".$index.".Intimark_peso_bruto", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::text("orderProducts[".$index."][Intimark_peso_tara]", false, old("orderProducts[".$index."][Intimark_peso_tara]"), ["wire:model" => "orderProducts.".$index.".Intimark_peso_tara", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::text("orderProducts[".$index."][Intimark_peso_neto]", false, old("orderProducts[".$index."][Intimark_peso_neto]"), ["wire:model" => "orderProducts.".$index.".Intimark_peso_neto", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][bodega_id]", false, $allBodegas, old("orderProducts[".$index."][bodega_id]"), ["wire:model"=>"orderProducts.".$index.".bodega_id", "required"=>true]); !!}
                    </td>
                    <td>
                        <a href="#" wire:click.prevent="removeProduct({{$index}})"><i class="tio-delete"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-sm btn-light"
                    wire:click.prevent="addProduct"><i class="tio-add"></i> Agregar material</button>
            </div>
        </div>
    </div>
</div>
