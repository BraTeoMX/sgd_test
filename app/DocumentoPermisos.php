<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class documentopermisos extends Model
{
    //
    protected $table = 'permisos';

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
        'saldo_dias',
    ];
}