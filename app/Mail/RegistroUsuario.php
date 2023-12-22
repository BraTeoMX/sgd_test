<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistroUsuario extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        /*return $this->from('nominas@intimark.com.mx')
                    ->subject('Registro de usuario del Sistema '.config('app.name'))
                    ->markdown('mails.registrousuario');
                    //->attach('mails/portada.pdf');*/

        return $this->from('nominas@intimark.com.mx')
                    ->subject('Registro de usuario del Sistema '.config('app.name'))
                    ->markdown('mails.registrousuario');
    }
}
