<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment; // <--- Importante agregar esta línea
use Illuminate\Queue\SerializesModels;

class NotificarSeguimientoMailable extends Mailable
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
            subject: 'Oficio Concluido - Respuesta Generada: ' . $this->oficio->numero_oficio,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.seguimiento.notificacion',
        );
    }

    // --- FUNCIÓN PARA ADJUNTAR EL PDF ---
    public function attachments(): array
    {
        // Verificamos si realmente hay un archivo guardado
        if ($this->oficio->soporte_documental) {
            return [
                // Usamos fromStorageDisk para que lo busque directo en 'storage/app/public'
                Attachment::fromStorageDisk('public', $this->oficio->soporte_documental)
            ];
        }

        return [];
    }
}