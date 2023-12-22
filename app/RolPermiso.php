<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 */
class RolPermiso extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'model_has_roles';

   

}
