<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use App\Estado;

class EstadosImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            DatoCurricularPrecarga::insert([
                'id' => $row['estado_id'],
                'estado' => $row['estado'],
                'digito_curp' => $row['nomenclatura_curp'],
            ]);
        }
    }

    public function batchSize(): int
    {
            return 250;
    }

    public function chunkSize(): int
    {
            return 250;
    }

    public function getRowCount(): int
   {
       return $this->total_registros;
   }
}
