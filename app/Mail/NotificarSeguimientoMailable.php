<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificarSeguimientoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $oficio;
    public $emisor;       // Quien envía (Jesús Daniel)
    public $destinatario; // Quien recibe

    public function __construct($oficio, $emisor, $destinatario)
    {
        $this->oficio = $oficio;
        $this->emisor = $emisor;
        $this->destinatario = $destinatario;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Oficio Concluido - Requiere Respuesta: ' . $this->oficio->numero_oficio,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.seguimiento.notificacion',
        );
    }
}