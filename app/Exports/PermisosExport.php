<?php

namespace App\Exports;

use App\FaltasJustificadas;
use App\FormatoPermisos;
use App\Vacaciones;
use App\Incapacidades;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PermisosExport implements FromQuery, WithHeadings, WithCustomCsvSettings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct(string $fecha1, string $fecha2, string $motivo)
    {
        $this->fecha1 = $fecha1;
        $this->fecha2 = $fecha2;
        $this->motivo = $motivo;
    }

    public function query()
    {
        if ($this->motivo === 'JustiHoras') {
            return FormatoPermisos::query()->join('cat_permisos', 'cat_permisos.id_permiso', 'permisos.tipo_per')
                ->select(
                    #'permisos.fk_no_empleado',
                    DB::raw("LPAD(permisos.fk_no_empleado,7,'0')"),
                    #'cat_permisos.cve_permiso',
                    DB::raw("IF (cat_permisos.forma = 3 AND permisos.modo_per = 1, 'HEntrada',
                    IF(cat_permisos.forma = 3 AND permisos.modo_per = 2, 'HSalida',
                    IF(cat_permisos.forma = 3 AND permisos.modo_per = 3, 'HEntraSale',
                    IF(cat_permisos.forma = 4 AND permisos.modo_per = 2, 'SalidaAnte',
                    IF(cat_permisos.forma = 4 AND permisos.modo_per = 3,'SaleEntra',
                    IF(cat_permisos.forma = 4 AND permisos.modo_per = 1 AND cat_permisos.id_permiso = 35, 'Retardo','EntraTarde' )))))) AS IdJustificacion"),
                    DB::raw("DATE_FORMAT(fech_ini_per, '%Y%m%d') AS fech_ini_per"),
                    DB::raw("DATE_FORMAT(fech_fin_per, '%Y%m%d') AS fech_fin"),
                    'permisos.horas',
                    DB::raw("'SGD_Asis' AS usuario"),
                    #DB::raw("CASE WHEN cat_permisos.tipo = 1 THEN 'POR DIA' ELSE 'POR HORA' END AS Referencia"),
                    #DB::raw("CASE WHEN cat_permisos.forma = 1 THEN 'CON GOCE' ELSE 'SIN GOCE' END AS Comentario"),
                    'cat_permisos.cve_permiso',
                    'cat_permisos.permiso',
                    DB::raw("'003' AS idempresa"),
                )
                ->where('fech_ini_per', '>=', $this->fecha1)
                ->where('fech_fin_per', '<=', $this->fecha2)
                ->where('cat_permisos.tipo', '2')
                ->where('Status', 'APLICADO')
                ->orderBy('idPermiso', 'ASC');
        } else if ($this->motivo === 'JustiDia') {
            return FormatoPermisos::query()->join('cat_permisos', 'cat_permisos.id_permiso', 'permisos.tipo_per')
                ->select(
                    #'permisos.fk_no_empleado',
                    DB::raw("LPAD(permisos.fk_no_empleado,7,'0')"),
                    'cat_permisos.cve_permiso',
                    DB::raw("DATE_FORMAT(fech_ini_per, '%Y%m%d') AS fech_ini_per"),
                    DB::raw("DATE_FORMAT(fech_fin_per, '%Y%m%d') AS fech_fin"),
                    DB::raw("'0' AS DatoCaptura"),
                    DB::raw("'SGD_Asis' AS usuario"),
                    DB::raw("CASE WHEN cat_permisos.tipo = 1 THEN 'POR DIA' ELSE 'POR HORA' END AS Referencia"),
                    DB::raw("CASE WHEN cat_permisos.forma = 1 THEN 'CON GOCE' ELSE 'SIN GOCE' END AS Comentario"),
                    DB::raw("'003' AS idempresa"),
                )
                ->where('fech_ini_per', '>=', $this->fecha1)
                ->where('fech_fin_per', '<=', $this->fecha2)
                ->where('cat_permisos.tipo', '1')
                ->where('Status', 'APLICADO')
                ->orderBy('idPermiso', 'ASC');
        } else if ($this->motivo === 'JustiVac') {
            return Vacaciones::query()
                ->select(
                    #'fk_no_empleado',
                    DB::raw("LPAD(fk_no_empleado,7,'0')"),
                    DB::raw("'Vac' AS IdJustificacion"),
                    DB::raw("DATE_FORMAT(fech_ini_vac, '%Y%m%d') AS fech_ini_vac"),
                    DB::raw("DATE_FORMAT(fech_fin_vac, '%Y%m%d') AS fech_vac"),
                    DB::raw("'0' AS DatoCaptura"),
                    DB::raw("'SGD_Asis' AS usuario"),
                    #DB::raw("'POR DIA' AS Referencia"),
                    'folio_vac',
                    #DB::raw("'CON GOCE' AS Comentario"),
                    DB::raw("IF(periodos = 1, 'Periodo', IF(eventualidades = 1, 'Eventualidad', '')) AS Comentario"),
                    DB::raw("'003' AS idempresa")
                )
                ->where('status', 'APLICADO')
                ->where('fech_ini_vac', '>=', $this->fecha1)
                ->where('fech_fin_vac', '<=', $this->fecha2)
                ->orderBy('IdVacaciones', 'ASC');
        } else if ($this->motivo === 'JustiFalta') {
            return FaltasJustificadas::query()->join('cat_motivos_faltasjusti', 'cat_motivos_faltasjusti.id', 'faltas_justificadas.motivo_falta')
                ->select(
                    #'faltas_justificadas.fk_no_empleado',
                    DB::raw("LPAD(faltas_justificadas.fk_no_empleado,7,'0')"),
                    DB::raw("'InasJusti' AS IdJustificacion"),
                    DB::raw("DATE_FORMAT(faltas_justificadas.fecha_inicio_justificar, '%Y%m%d') AS fecha_inicio_justificar"),
                    DB::raw("DATE_FORMAT(faltas_justificadas.fecha_fin_justificar, '%Y%m%d') AS fecha_fin_justificar"),
                    DB::raw("'0' AS DatoCaptura"),
                    DB::raw("'SGD_Asis' AS usuario"),
                    #DB::raw("'POR DIA' AS Referencia"),
                    'faltas_justificadas.folio_falta',
                    #DB::raw("'CON GOCE' AS Comentario"),
                    'cat_motivos_faltasjusti.motivo',
                    DB::raw("'003' AS idempresa")
                )
                ->where('faltas_justificadas.fecha_inicio_justificar', '>=', $this->fecha1)
                ->where('faltas_justificadas.fecha_fin_justificar', '<=', $this->fecha2)
                ->orderBy('faltas_justificadas.id', 'ASC');
        } else if ($this->motivo === 'JustiInca') {
            return Incapacidades::select(
                'fk_empleado',
                DB::raw('IF(ramo_seguro = 1, "IncEnfe", "IncMater")'),
                //'fecha_inicio',
                //'fecha_fin',
                DB::raw("DATE_FORMAT(fecha_inicio, '%Y%m%d') as fecha_inicio"),
                DB::raw("DATE_FORMAT(fecha_fin, '%Y%m%d') as fecha_fin"),
                DB::raw('"0" AS DatoCaptura'),
                DB::raw('"SGD_Asis" AS usuario'),
                'folio_incapacidad',
                DB::raw('IF(riesgo = 01, "04",IF(ramo_seguro = 02,"06",IF(tipo_incapacidad = 02, "02",IF(tipo_incapacidad = 01, "01",""))))'),
                DB::raw("'003' AS idempresa")
            )
            ->where('status', 'APLICADO')
            ->where('fecha_inicio', '>=', $this->fecha1)
            ->where('fecha_fin', '<=', $this->fecha2)
            ->orderBy('id', 'ASC'); ;
        }
    }

    public function headings(): array
    {
        return [
            'IdPoblacion',
            'IdJustificacion',
            'FechaInicial',
            'FechaFinal',
            'DatoCaptura',
            'IdUsuario',
            'Referencia',
            'Comentario',
            'idempresa'
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'enclosure' => '',
        ];
    }
}
