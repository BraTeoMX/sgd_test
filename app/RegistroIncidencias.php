<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

/**
 * @property integer $id
 * @property string $categoria
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class RegistroIncidencias extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'registro_incidencias';

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
       
        'Id',
        'folio',
        'tipo',
        'status'

    ];

    
}
