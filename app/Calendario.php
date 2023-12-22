<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_calendario';

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
        
        'id',
        'fecha_calendario',
        'tipo',
        'tipo_nomina',
        'id_modulo',
        'estatus_fecha'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'fecha_solicitado' => 'datetime',
    ];
}
