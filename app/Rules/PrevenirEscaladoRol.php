<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Role;
use App\User;

class PrevenirEscaladoRol implements Rule
{
    private $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $rol = Role::Where('name', $value)->first();
        if(!filled($rol)) {
            $this->message = 'El rol: '.$value.', no se encuentra registrado';
            return false;
        }

        $rol = Role::where('id', '<', auth()->user()->roles()->first()->id)->first();

        if(filled($rol)) {
            if($rol->id <= auth()->user()->roles()->first()->id) {
                $this->message = 'No tiene permitido asignar al nuevo usuario el rol: ' . $value;
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    public function __toString()
    {
      return 'Verifica Rol';
    }
}
