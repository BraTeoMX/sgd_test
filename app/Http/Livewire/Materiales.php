<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Entrada;
use App\EntradaDetalle;
use App\Material;
use App\Bodega;
use App\TipoMaterial;

class Materiales extends Component
{
    public $orderProducts = [];
    public $allMateriales = [];
    public $allBodegas = [];
    public $allTipoMaterial = [];

    public function mount()
    {
        $this->allMateriales = Material::all()->pluck('material','id');
        $this->allBodegas = Bodega::all()->pluck('bodega','id');
        $this->allTipoMaterial = TipoMaterial::all()->pluck('tipo_material','id');
        $this->orderProducts = [
            [
                'folio_intimark'=>'',
                'material_id'=>'',
                'tipo_material_id'=>'',
                'peso_cliente'=>'',
                'intimark_peso_bruto'=>'',
                'intimark_peso_tara'=>'',
                'intimark_peso_neto'=>'',
                'bodega_id'=>'',
            ]
        ];
        if(filled(Entrada::find(session('entrada_id')))) {
            $this->orderProducts = EntradaDetalle::where('entrada_id', session('entrada_id'))->get()->toArray();
        }

    }

    public function addProduct()
    {
        $this->orderProducts[] = [
            'folio_intimark'=>'',
            'material_id'=>'',
            'tipo_material_id'=>'',
            'peso_cliente'=>'',
            'intimark_peso_bruto'=>'',
            'intimark_peso_tara'=>'',
            'intimark_peso_neto'=>'',
            'bodega_id'=>'',
        ];
    }

    public function removeProduct($index)
    {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
    }

    public function render()
    {
        info($this->orderProducts);
        return view('livewire.materiales');
    }
}
