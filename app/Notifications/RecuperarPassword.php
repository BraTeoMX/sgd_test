<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RecuperarPassword extends Notification
{
    use Queueable;
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->from($notifiable->email)
        ->subject('Restablecimiento de contraseña del Sistema '.config('app.name', 'intimark')) //agregamos el asunto
        ->greeting('Estimado (a) ' . ucfirst(strtolower($notifiable->name)))// titulo del mensaje
        ->line('Hemos recibido una solicitud para restablecer su contraseña del '.config('app.name', 'intimark').', favor de dar clic en el siguiente botón.')
        ->action('Restablecer contraseña', url(route('password.reset', $this->token, false)))
        ->line('Por motivos de seguridad, solo puede restablecer la contraseña a través de este botón, durante las 24 horas posteriores.')
        ->line('Cabe hacer mención que la generación y resguardo de la contraseña es su responsabilidad.')
        ->line('En caso de no haber realizado la solicitud de restablecimiento, hacer caso omiso a este correo.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
