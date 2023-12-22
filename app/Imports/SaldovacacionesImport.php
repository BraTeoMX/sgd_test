<?php

namespace App\Imports;

use App\saldovacaciones;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SaldovacacionesImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    private $rows = 1;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ++$this->rows;

        return new saldovacaciones([
            //
            'no_trabajador' => $row['cvetra'],
            'nom_trabajador' => $row['nombre'].' '.$row['apepat'].' '.$row['apemat'],
            'saldo_dias_anterior' => $row['saldo_vaca_anterior'],
            'saldo_dias_nuevo' => $row['saldo_vaca_nuevo'],
            'saldo_dias_proporc' => $row['saldo_vaca_proporc'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.cvetra' => [
                'required',
                'int',
            ],
            '*.nombre' => [
                'required',
                'string',
            ],
            '*.apepat' => [
                'required',
                'string',
            ],
            '*.saldo_vaca_anterior' => [
                'required',
                'int',
            ],
         /*   '*.saldo_vaca_nuevo' => [
                'required',
                'int',
            ],
            '*.saldo_vaca_proporc' => [
                'required',
                'int',
            ],*/
        ];
    }

    public function getRowCount(): int
    {
        return $this->rows-1;
    }

}
