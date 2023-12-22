<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitudDenegada extends Mailable
{
    use Queueable, SerializesModels;
    public $vacaciones;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($vacaciones)
    {
        $this->vacaciones = $vacaciones;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('nominas@intimark.com.mx')
                    ->subject('Solicitud de Vacaciones ')
                    ->markdown('mails.solicituddenegada');
       
    }
}
