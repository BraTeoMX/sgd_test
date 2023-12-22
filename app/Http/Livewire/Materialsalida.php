<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Salida;
use App\SalidaDetalle;
use App\Material;

class Materialsalida extends Component
{
    public $orderProducts = [];
    public $allMateriales = [];
    public $allBodegas = [];
    public $allTipoMaterial = [];

    public function mount()
    {
        $this->allMateriales = Material::all()->pluck('material','id');
        $this->orderProducts = [
            [
                'material_id'=>'',
                'intimark_peso_neto'=>'',
            ]
        ];
        if(filled(Salida::find(session('salida_id')))) {
            $this->orderProducts = SalidaDetalle::where('salida_id', session('salida_id'))->get()->toArray();
        }

    }

    public function addProduct()
    {
        $this->orderProducts[] = [
            'material_id'=>'',
            'intimark_peso_neto'=>'',
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
        return view('livewire.materialsalida');
    }
}
