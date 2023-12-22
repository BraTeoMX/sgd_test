<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;


class ReporteEventosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $reportesQuery;
    private $tipoEvento;

    public function __construct($datosReporte)
    {
        $this->reportesQuery = $datosReporte['reportesQuery'];
        $this->tipoEvento = $datosReporte['tipoEvento'];
    }

    public function collection()
    {
        return $this->reportesQuery;
    }

    public function headings(): array
    {
        $primerEvento = $this->reportesQuery->first();

        $cabeceras = [
            'No Empleados',
            'No Tag',
            'Tipo Evento',
            'Nombre Empleado',
            'Puesto',
            'Departamento',
            'Planta',
            'Asistencia',
            'Fecha',
        ];

        if ($primerEvento && $primerEvento->id_evento == 5) {
            $cabeceras = [
                'No Empleados',
                'No Tag',
                'Tipo Evento',
                'Nombre Empleado',
                'Puesto',
                'Departamento',
                'Planta',
                'Rollos',
                'Fecha',
            ];
        }

        return $cabeceras;
    }

    public function map($reporte): array
    {
        $data = [
            $reporte->no_empleados,
            $reporte->No_Tag,
            $reporte->tipo_evento,
            $reporte->nombre_empleado,
            $reporte->Puesto,
            $reporte->Departamento,
            $reporte->Planta == 'Intimark1' ? 'Ixtlahuaca' : ($reporte->Planta == 'Intimark2' ? 'San Bartolo' : $reporte->Planta),
            $reporte->asistencia ?? 'No confirmó asistencia',
            $reporte->created_at,
        ];

        if ($reporte->id_evento == 5) {
            $data = [
                $reporte->no_empleados,
                $reporte->No_Tag,
                $reporte->tipo_evento,
                $reporte->nombre_empleado,
                $reporte->Puesto,
                $reporte->Departamento,
                $reporte->Planta == 'Intimark1' ? 'Ixtlahuaca' : ($reporte->Planta == 'Intimark2' ? 'San Bartolo' : $reporte->Planta),
                $cabeceras[7] = '2',
                $reporte->created_at,
            ];
        }if ($reporte->id_evento == 8) {
            $data = [
                $reporte->no_empleados,
                $reporte->No_Tag,
                $reporte->tipo_evento,
                $reporte->nombre_empleado,
                $reporte->Puesto,
                $reporte->Departamento,
                $reporte->Planta == 'Intimark1' ? 'Ixtlahuaca' : ($reporte->Planta == 'Intimark2' ? 'San Bartolo' : $reporte->Planta),
                $reporte->asistencia == 'Presente' ? 'Despensa entregada' :( $reporte->asistencia  == 'Presente' ? 'Despensa entregada' : $reporte->asistencia),
                $reporte->created_at,
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['argb' => 'FFA0A0A0'],
                'endColor' => ['argb' => 'FFFFFFFF'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['argb' => 'FFD9D9D9'],
                'endColor' => ['argb' => 'FFFFFFFF'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }
}
