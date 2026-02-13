<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesNuevoUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $password;

    /**
     * Recibimos el usuario y la contraseña temporal
     */
    public function __construct(User $usuario, $password)
    {
        $this->usuario = $usuario;
        $this->password = $password;
    }

    /**
     * Construimos el mensaje
     */
    public function build()
    {
        return $this->subject('Bienvenido al sistema, Credenciales de acceso')
                    ->view('emails.credenciales'); // Aquí le decimos qué vista usar
    }
}