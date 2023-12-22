<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $chofer_id
 * @property integer $sucursal_id
 * @property integer $usuario_creacion_id
 * @property integer $usuario_actualizacion_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class CatalogoPermisos extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'cat_permisos';

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
        'id_permiso', 
        'permiso', 
        'tipo', 
        'forma',
        'firma_vp',
        'documento',
        'acumula',
        'ausentismo', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];


}
