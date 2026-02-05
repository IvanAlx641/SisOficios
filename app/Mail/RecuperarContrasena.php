<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarContrasena extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        // Asegúrate de que la vista 'emails.recuperar' exista también
        return $this->subject('Restablecer Contraseña - Sistema de Oficios')
                    ->view('emails.recuperar');
    }
}