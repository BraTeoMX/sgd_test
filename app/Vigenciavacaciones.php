<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vigenciavacaciones extends Model
{
    //
    protected $table = 'vigencia_vacaciones';

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
        'feha_aniversario',
        'saldo_anterior',
        'vigencia_anterior',
        'event_anterior',
        'periodo_anterior',
        'saldo_nuevo',
        'vigencia_nuevo',
        'event_nuevo',
        'periodo_nuevo',
    ];
}
