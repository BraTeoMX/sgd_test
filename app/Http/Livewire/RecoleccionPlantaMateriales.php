<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\RolLogistica;
use App\RolLogisticaDetalle;
use App\Material;
use App\Bodega;
use App\TipoMaterial;
use App\Sucursal;
use App\Cliente;
use App\ClienteSitio;

class RecoleccionPlantaMateriales extends Component
{
    public $orderProducts = [];
    public $allMateriales = [];
    public $allBodegas = [];
    public $allSucursales = [];
    public $allClientes = [];
    public $allClienteSitios = [];

    public function mount()
    {
        $this->allMateriales = Material::all()->pluck('material','id');
        $this->allBodegas = Bodega::all()->pluck('bodega','id');
        $this->allSucursales = Sucursal::all()->pluck('sucursal','id');
        $this->allClientes = Cliente::where('comprador', '1')->get()->pluck('nombre_comercial','id');
        $this->allClienteSitios = ClienteSitio::all()->pluck('sitio','id');
        $this->orderProducts = [
            [
                'id'=>'',
                'material_id'=>'',
                'sucursal_id'=>'',
                'sitio_id'=>'',
                'folio_planta'=>'',
                'peso_tara_planta'=>'',
                'peso_bruto_planta'=>'',
                'total_planta'=>'',
                'recoleccion_asitencia'=>'',
                'hora_llegada_planta'=>'',
                'hora_salida_planta'=>''
            ]
        ];

        if(filled(RolLogistica::find(session('rollogistica_identi')))) {
            
            $this->orderProducts = RolLogisticaDetalle::where('roles_logistica_id', session('rollogistica_identi'))->get()->toArray();
        }
        

    }

    public function render()
    {
        return view('livewire.recoleccion-planta-materiales');
    }
}
