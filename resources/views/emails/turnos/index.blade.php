<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notificación de Oficio Turnado</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-top: 4px solid #9D2449; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { background-color: #f9f9f9; padding: 15px; border-radius: 5px; }
        .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #777; }
        .highlight { font-weight: bold; color: #9D2449; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Se le ha turnado un nuevo oficio</h2>
        </div>
        
        <p>Hola, <strong>{{ $destinatario->nombre ?? 'Usuario' }}</strong></p>
        
        <p>Se le notifica que se le ha asignado el oficio <span class="highlight">{{ $oficio->numero_oficio }}</span> para su atención y seguimiento.</p>

        <div class="content">
            <p><strong>Detalles de la asignación:</strong></p>
            <ul>
                <li><strong>Asignado por:</strong> {{ $emisor->nombre ?? 'Usuario del Sistema' }}</li>
                <li><strong>Área de origen (Dirigido a):</strong> {{ optional($oficio->areaDirigido)->nombre_unidad_administrativa ?? 'N/A' }}</li>
            </ul>

            <p><strong>Solicitantes:</strong></p>
            @if ($oficio->solicitantes->count() > 0)
                <ul>
                    @foreach ($oficio->solicitantes as $solicitante)
                        <li>{{ $solicitante->nombre }} - <em>{{ $solicitante->cargo }}</em></li>
                    @endforeach
                </ul>
            @else
                <p><em>Sin solicitantes registrados.</em></p>
            @endif
        </div>

        <p>Por favor, ingrese al sistema para ver el documento completo y los detalles adicionales.</p>

        <div class="footer">
            <p>Este es un mensaje automático generado por el Sistema de Turnos. No responda a este correo.</p>
        </div>
    </div>
</body>
</html>