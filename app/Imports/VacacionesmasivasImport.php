<?php

namespace App\Imports;

use App\Http\Controllers\VacacionesController;
use App\Puestos;
use App\Tbl_Empleado_SIA;
use App\Vacaciones;
use App\Vacacionesmasivas;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class VacacionesmasivasImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    private $rows = 1;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        /*echo substr($row['fec_ini'],0,4);
        echo substr($row['fec_ini'],5,2);
        echo substr($row['fec_ini'],8,2);
        */
        $serie = str_pad($this->rows,4,0, STR_PAD_LEFT);
        $jefedir = '1477';
        ++$this->rows;

        /*return new Vacacionesmasivas([
            'fecha_solicitud' => $fecha,
            'fecha_aprovacion' => $fecha,
            'no_empleado' => $row['cvetra'],
            'fecha_ini' => $row['fec_ini'],
            'fecha_fin' => $row['fec_fin'],

        ]);*/

        //dd(substr($row['fec_ini'],4,1));
        if(substr($row['fec_ini'],4,1) != "-"){ 
        $fecha_ini = is_numeric($row['fec_ini'])
                ? Carbon::create(1900, 1, 1)
                    ->addDays(intval($row['fec_ini']) - 2)
                    ->format('Y-m-d')
                : null;

            $fecha_fin = is_numeric($row['fec_fin'])
                ? Carbon::create(1900, 1, 1)
                    ->addDays(intval($row['fec_fin']) - 2)
                    ->format('Y-m-d')
                : null;
        }else {
            $fecha_ini = $row['fec_ini'];
            $fecha_fin =$row['fec_fin'];
            
        }

        return new Vacaciones([
            'folio_vac' => substr($row['fec_ini'],2,2).substr($row['fec_ini'],5,2).$serie.'V',
            'fecha_solicitud' => date('Y-m-d'),
            'fecha_aprobacion' => date('Y-m-d'),
            'fk_no_empleado' => $row['cvetra'],
            'dias_solicitud' => $row['dias_sol'],
            'fech_ini_vac' => $fecha_ini,
            'fech_fin_vac' => $fecha_fin,
            'jefe_directo' => $jefedir,
            'status' => 'APLICADO',
            'created_at' => date('Y-m-d H-m-s'),
            'updated_at' => date('Y-m-d H-m-s'),
            'fecha_aprobacion' => date('Y-m-d'),
            
        ]);
        

    }


    public function rules(): array
    {
        return [
            '*.cvetra' => [
                'required',
                'int'
            ],
            '*.fec_ini' => [
                'required',
                'date'
            ],
            '*.fec_fin' => [
                'required',
                'date'
            ],
            '*.dias_sol' => [
                'required',
                'int'
            ],
        ];
    }



}
