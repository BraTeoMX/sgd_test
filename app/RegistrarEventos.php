<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrarEventos extends Model
{
    protected $table = 'cat_eventos';

    public $timestamps = false;
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
        'cve_evento',
        'tipo_evento',
        'conf_pre_regis',
        'depart_invo',

    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];



}
?>
