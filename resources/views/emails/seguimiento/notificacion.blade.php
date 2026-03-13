<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Generación de Respuesta - Oficio Concluido</title>
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
            <h2>Generar respuesta</h2>
        </div>
        
        <p>Hola, <strong>{{ $destinatario->nombre ?? 'Usuario' }}</strong></p>
        
        <p>Se le notifica que el oficio <span class="highlight">{{ $oficio->numero_oficio }}</span> ha sido marcado como <strong>Concluido</strong>.</p>

        <div class="content">
            <p>El sistema ahora le permite proceder con la generación de la respuesta correspondiente para este requerimiento.</p>
            <ul>
                <li><strong>Notificado por:</strong> {{ $emisor->nombre ?? 'Usuario del Sistema' }}</li>
                <li><strong>Sistema:</strong> {{ optional($oficio->sistema)->sigla_sistema ?? 'N/A' }}</li>
            </ul>
        </div>

        <p>Por favor, ingrese al módulo de Seguimiento para redactar y subir la respuesta.</p>

        <div class="footer">
            <p>Este es un mensaje automático generado por el Sistema de Oficios. No responda a este correo.</p>
        </div>
    </div>
</body>
</html>