<div class="card">
    <div class="card-header">
        <h4>Destino de los materiales</h4>
    </div>

    <div class="card-body">
        <table class="table" id="products_table">
            <thead>
            <tr>
                <th>Material</th>
                <th>Km/Recolección</th>
                <th>Sucursal</th>
                <th>Bodega</th>
                <th>Cliente destino</th>
                <th>Sitio/Lugar</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orderProducts as $index => $orderProduct)
                <tr>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][material_id]", false, $allMateriales, old("orderProducts[".$index."][material_id]"), ["wire:model"=>"orderProducts.".$index.".material_id", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::text("orderProducts[".$index."][kilometraje_recoleccion]", false, old("orderProducts[".$index."][kilometraje_recoleccion]"), ["wire:model"=>"orderProducts.".$index.".kilometraje_recoleccion", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][sucursal_id]", false, $allSucursales, old("orderProducts[".$index."][sucursal_id]"), ["wire:model"=>"orderProducts.".$index.".sucursal_id", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][bodega_id]", false, $allBodegas, old("orderProducts[".$index."][bodega_id]"), ["wire:model"=>"orderProducts.".$index.".bodega_id", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][cliente_id]", false, $allClientes, old("orderProducts[".$index."][cliente_id]"), ["wire:model"=>"orderProducts.".$index.".cliente_id", "required"=>true]); !!}
                    </td>
                    <td>
                        {!! BootForm::select("orderProducts[".$index."][sitio_id]", false, $allClienteSitios, old("orderProducts[".$index."][sitio_id]"), ["wire:model"=>"orderProducts.".$index.".sitio_id", "required"=>true]); !!}
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
