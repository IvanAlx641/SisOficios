<?php

namespace App\Mail;

// Ya no necesitamos importar el modelo User aquí
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarContrasena extends Mailable
{
    use Queueable, SerializesModels;

    // Solo declaramos las dos variables que nos manda el controlador
    public $token;
    public $email;

    // Recibimos estrictamente: 1. Token, 2. Email
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        // Usamos tu método build() original y apuntamos a tu vista 'emails.recuperar'
        return $this->subject('Restablecer Contraseña - Sistema de Oficios')
                    ->view('emails.recuperar');
    }
}