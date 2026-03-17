<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notificación de Registro de Oficio</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-top: 4px solid #9D2449; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 100%; height: auto; max-height: 80px; margin-bottom: 15px; }
        .content { background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #777; border-top: 1px solid #ddd; padding-top: 15px; }
        .highlight { font-weight: bold; color: #9D2449; }
        ul { margin-top: 5px; padding-left: 20px; }
        .btn { display: inline-block; padding: 10px 20px; color: #ffffff; background-color: #9D2449; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('materialpro/assets/images/morenaicons/logos.jpg')) }}" alt="Logos Institucionales">
            <h2 style="margin: 0; color: #333;">
                Nuevo oficio registrado 
            </h2>
        </div>
        
        <p>Estimado(a) <strong>{{ $destinatario->nombre ?? 'Titular de Área' }}</strong>,</p>
        
        <p>Se ha registrado un nuevo oficio <a href="{{ $oficio->url_oficio }}" style="color: #9D2449; text-decoration: none;">{{ $oficio->numero_oficio }}</a>.</p>

        <div class="content">
            <p><strong>Detalles del oficio:</strong></p>
            <ul>
                <li><strong>Descripción:</strong> {{ $oficio->descripción_oficio }}</li>
            </ul>

            <p><strong>Solicitado por:</strong></p>
            @if ($oficio->solicitantes->count() > 0)
                <ul>
                    @foreach ($oficio->solicitantes as $solicitante)
                        <li>{{ $solicitante->nombre }} - <em>{{ $solicitante->cargo }}</em></li>
                    @endforeach
                </ul>
            @else
                <p><em>{{ $nombresSolicitantes ?: 'Sin solicitantes registrados.' }}</em></p>
            @endif

            
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Derechos Reservados. Secretaría de la Contraloría del Estado de México.</p>
        </div>
    </div>
</body>
</html>