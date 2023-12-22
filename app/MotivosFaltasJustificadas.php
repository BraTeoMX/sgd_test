<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotivosFaltasJustificadas extends Model
{
    //
    use SoftDeletes;

    protected $table = 'cat_motivos_faltasjusti';

    protected $keyType = 'integer';

    protected $fillable = [
        'id',
        'motivo',
    ];



}
