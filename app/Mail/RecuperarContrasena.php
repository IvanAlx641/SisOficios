<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarContrasena extends Mailable
{
    use Queueable, SerializesModels;

    // Al ponerlas como 'protected', evitamos que Laravel las cruce por error
    protected $tokenTexto;
    protected $emailDestino;

    public function __construct($token, $email)
    {
        $this->tokenTexto = $token;
        $this->emailDestino = $email;
    }

    public function build()
    {
        return $this->subject('Restablecer contraseña - Sistema de Control de Oficios.')
                    ->view('emails.recuperar')
                    ->with([
                        // Aquí obligamos a la vista a recibir exactamente el nombre correcto
                        'token' => $this->tokenTexto,
                        'email' => $this->emailDestino,
                    ]);
    }
}