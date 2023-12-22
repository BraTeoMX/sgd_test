<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventualidadPeriodo extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eventualidad_periodo';

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
        'no_empleado',
        'eventualidad',
        'periodo',
        'anio',
        'deleted_at'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
}
