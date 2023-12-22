<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faltas extends Model
{
    //
    use SoftDeletes;

    protected $table = 'faltas';

    protected $keyType = 'integer';

    protected $fillable = [
        'id',
        'no_empleado',
        'fecha_falta',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


}
