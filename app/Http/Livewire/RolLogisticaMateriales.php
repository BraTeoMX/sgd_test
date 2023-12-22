<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\RolLogistica;
use App\RolLogisticaDetalle;
use App\ConvenioDetalle;
use App\Material;
use App\Bodega;
use App\TipoMaterial;
use App\Sucursal;
use App\Cliente;
use App\ClienteSitio;

class RolLogisticaMateriales extends Component
{
    public $orderProducts = [];
    public $allMateriales = [];
    public $allBodegas = [];
    public $allSucursales = [];
    public $allClientes = [];
    public $allClienteSitios = [];

    public function mount()
    {
        //$this->allMateriales = Material::all()->pluck('material','id');
        $this->allMateriales = ConvenioDetalle::with('catMateriales')->get()->pluck('material_cliente', 'catMateriales.id')->prepend('-Selecciona un material-','');
        $this->allBodegas = Bodega::all()->pluck('bodega','id')->prepend('-Selecciona una bodega-','');
        $this->allSucursales = Sucursal::all()->pluck('sucursal','id')->prepend('-Selecciona una sucursal-','');
        $this->allClientes = Cliente::where('comprador', '1')->get()->pluck('nombre_comercial','id')->prepend('-Selecciona un cliente destino-','');
        $this->allClienteSitios = ClienteSitio::all()->pluck('sitio','id')->prepend('-Selecciona un sitio-','');
        $this->orderProducts[0] = [
            'material_id'=>'',
            'kilometraje_recoleccion'=>'',
            'sucursal_id'=>'',
            'bodega_id'=>'',
            'cliente_id'=>'',
            'sitio_id'=>'',
        ];
        if(filled(RolLogisticaDetalle::find(session('rol_logistica_id')))) {
            $this->orderProducts = RolLogisticaDetalle::where('roles_logistica_id', session('rol_logistica_id'))->get()->toArray();
        }

    }

    public function addProduct()
    {
        $this->orderProducts[] = [
            'material_id'=>'',
            'kilometraje_recoleccion'=>'',
            'sucursal_id'=>'',
            'bodega_id'=>'',
            'cliente_id'=>'',
            'sitio_id'=>'',
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
        return view('livewire.rol-logistica-materiales');
    }
}
