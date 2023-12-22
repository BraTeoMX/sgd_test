<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitudAprobadaPermisos extends Mailable
{
    use Queueable, SerializesModels;
    public $permisos;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($permisos)
    {
        $this->permisos = $permisos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('nominas@intimark.com.mx')
                    ->subject('Solicitud de Permisos ')
                    ->markdown('mails.solicitudaprobadapermisos');

    }
}
