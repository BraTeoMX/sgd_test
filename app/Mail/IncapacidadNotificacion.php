<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncapacidadNotificacion extends Mailable
{
    use Queueable, SerializesModels;
    public $incapacidad;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($incapacidad)
    {
        $this->incapacidad = $incapacidad;
        #dd($incapacidad);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('serviciomedico@intimark.com.mx')
            ->subject('Aviso de incapacidad ')
            ->markdown('mails.incapacidadregistrada');
    }
}
