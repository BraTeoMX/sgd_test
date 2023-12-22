<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromQuery, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct(int $year, int $year2)
    {
        $this->year = $year;
        $this->year2 = $year2;
    }

    public function query()
    {
        #return User::all();
        return User::query()->where('id', $this->year)->orWhere('id', $this->year2);
    }

    public function headings(): array
    {
        return [
            'id',
            'nombre',
            'correo',
            'NoEmpleado',
            'Rol',
            'fecha1',
            'fecha2'
        ];
    }
}
