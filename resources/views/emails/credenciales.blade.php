<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenido al sistema de oficios</title>
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
        
        /* Estilos específicos para la caja de credenciales */
        .credentials-box { 
            background-color: #ffffff; 
            border: 1px solid #e0e0e0; 
            border-left: 4px solid #C09F62; /* Dorado institucional */
            padding: 15px; 
            margin: 15px 0; 
            border-radius: 4px;
        }
        .label { color: #888; font-size: 13px; text-transform: uppercase; font-weight: bold; }
        .value { color: #333; font-weight: bold; font-family: monospace; font-size: 16px; margin-top: 5px; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('materialpro/assets/images/morenaicons/logos.jpg')) }}" alt="Logos Institucionales">
            <h2 style="margin: 0; color: #333;">Inicio de sesión</h2>
        </div>
        
        <p>Estimado(a) <strong>{{ $usuario->nombre }}</strong>,</p>
        
        <p>Te damos la bienvenida al <strong>Sistema de gestión de oficios</strong>. Tu cuenta ha sido creada exitosamente.</p>

        <div class="content">
            <p><strong>Tus credenciales de acceso temporal son las siguientes:</strong></p>
            
            <div class="credentials-box">
                <p style="margin: 0 0 15px 0;">
                    <span class="label">Correo Electrónico:</span>
                    <span class="value">{{ $usuario->email }}</span>
                </p>
                <p style="margin: 0;">
                    <span class="label">Contraseña temporal:</span>
                    <span class="value" style="color: #9D2449; font-size: 18px;">{{ $password }}</span>
                </p>
            </div>

            <p style="font-size: 13px; color: #666; font-style: italic;">
                * Por seguridad, el sistema te pedirá cambiar esta contraseña inmediatamente después de tu primer inicio de sesión.
            </p>

            <div class="text-center">
                <a href="{{ route('login') }}" class="btn" style="color: #ffffff;">Ingresar al Sistema</a>
            </div>
        </div>

        <div class="footer">
            <p>Este es un mensaje automático generado por el sistema. Por favor, no responda a este correo.</p>
            <p>&copy; {{ date('Y') }} Derechos Reservados. Secretaría de la Contraloría del Estado de México.</p>
        </div>
    </div>
</body>
</html>