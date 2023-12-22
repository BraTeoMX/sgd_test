<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class permiso_vacaciones extends Model
{
    //
    protected $table = 'permiso_vac';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    protected $fillable = [
        
        'idPermiso',
        'id_jefe',
        'id_puesto_solicitante'
    ];

    protected $casts = [
        'fecha_solicitado' => 'datetime',
    ];
}
