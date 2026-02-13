<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarContrasena extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $token;
    public $email;

    public function __construct(User $usuario,$token, $email)
    {
        $this->usuario = $usuario;
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        // Asegúrate de que la vista 'emails.recuperar' exista también
        return $this->subject('Restablecer Contraseña Sistema de Oficios')
                    ->view('emails.recuperar');
    }
}