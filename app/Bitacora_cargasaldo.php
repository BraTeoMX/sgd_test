<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bitacora_cargasaldo extends Model
{
    //
    protected $table = 'bitacora_cargasaldo';

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
        'fecha',
        'archivo',
        'no_registros',
    ];
}
