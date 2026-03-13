<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificarRegistroMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $oficio;
    public $emisor;
    public $destinatario;

    public function __construct($oficio, $emisor, $destinatario)
    {
        $this->oficio = $oficio;
        $this->emisor = $emisor;
        $this->destinatario = $destinatario;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo Oficio Registrado: ' . $this->oficio->numero_oficio,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.oficios.registro',
        );
    }
}