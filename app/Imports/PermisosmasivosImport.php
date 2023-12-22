<?php

namespace App\Imports;

use App\Http\Controllers\VacacionesController;
use App\Puestos;
use App\Tbl_Empleado_SIA;
use App\FormatoPermisos;
use App\Permisosmasivos;
use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PermisosmasivosImport implements ToModel, WithHeadingRow, SkipsEmptyRows
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
        if ($row['tipo_per']=='horas')
            $tipo_per='27';
        else
            $tipo_per = '6';
        if(isset($row['hora_salida']))
            $hora_salida=date("H:i:s", strtotime( $row['hora_salida']));
        else
            $hora_salida='00:00:00';


        if(date("N", strtotime($row['fec_ini']))== 5)
            $hora_final="14:00:00";
        else
            $hora_final="19:00:00";

            $fecha_ini =date("Y-m-d", strtotime( $row['fec_ini']));

        ++$this->rows;

        return new FormatoPermisos([
            'folio_per' => substr($row['fec_ini'],2,2).substr($row['fec_ini'],5,2).$row['cvetra'].'P',
            'tipo_per' => $tipo_per,
            'fecha_solicitud' => date('Y-m-d H:i:s'),
            'fecha_aprobacion' => date('Y-m-d'),
            'fk_no_empleado' => $row['cvetra'],
            'dias' => $row['dias_sol'],
            'fech_ini_hor' => $hora_salida,
            'fech_fin_hor' => $hora_final,
            'fech_ini_per' => date($row['fec_ini']),
            'fech_fin_per' => date($row['fec_fin']),
            'jefe_directo' => $jefedir,
            'modo_per' => $row['modo_per'],
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
            '*.tipo_per' => [
                'required',
                'varchar'
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
            '*.hora_salida' => [
                'required',
                'time'
            ],
            '*.modo_per' => [
                'required',
                'int'
            ],
        ];
    }



}
