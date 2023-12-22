<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrarAsistencias extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eventos';

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
        'id_evento',
        'no_empleados',
        'No_Tag',
        'tipo_evento',
        'PreRegistro',
        'asistencia',
        'nombre_empleado',
        'Puesto',
        'Planta',
        'Departamento',
        'fech_evento',
        'fech_evento_fin',
        'hora_registro',
        'pre_regis',
        'updated_at',
        'created_at',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];
}
