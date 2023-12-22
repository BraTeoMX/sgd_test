<?php

namespace App\Imports;

use App\FormatoPermisos;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FormatoPermisosImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $i = 1;

    public function getserie($fe)
    {
        $permiso = FormatoPermisos::select('folio_per')
            ->where('fech_ini_per', $fe)
            ->where('folio_per', 'LIKE', '%E')
            ->orderBy('IdPermiso', 'desc')
            ->get()->first();
        #echo $permiso;
        return $permiso;
    }

    public function model(array $row)
    {
        $fecha = date('ymd');
        if ($this->i === 1) {
            $folio = $this->getserie($row['fec_ini']);
            #echo $folio;
            if ($folio === null) {
                #echo 'está vacio';
            } else {
                #echo 'no está vacio';
                $parte = substr($folio->folio_per, 6, 4);
                $this->i = intval($parte) + 1;
            }
            #$parte = substr($folio->folio_per, 6, 4);
            #$this->i = intval($parte) + 1;
        } else {
            ++$this->i;
        }
        $this->i = str_pad($this->i, 4, 0, STR_PAD_LEFT);

        return new FormatoPermisos([
            //
            'folio_per' => substr($row['fec_ini'], 2, 2) . substr($row['fec_ini'], 5, 2) . substr($row['fec_ini'], 8, 2) . $this->i . 'E',
            'tipo_per' => '14',
            'fecha_solicitud' => $fecha,
            'fecha_aprobacion' => $fecha,
            'fk_no_empleado' => $row['cvetra'],
            'fech_ini_per' => $row['fec_ini'],
            'fech_fin_per' => $row['fec_fin'],
            'dias' => $row['dias_sol'],
            'jefe_directo' => '1477',
            'status' => 'APLICADO',
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
