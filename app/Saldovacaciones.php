<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saldovacaciones extends Model
{
    //
    protected $table = 'saldo_vacaciones';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = [
        'no_trabajador',
        'nom_trabajador',
        'saldo_dias_anterior',
        'saldo_dias_nuevo',
        'saldo_dias_proporc',
    ];
}
