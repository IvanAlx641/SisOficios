<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Restablecer Contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-top: 4px solid #9D2449; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 100%; height: auto; max-height: 80px; margin-bottom: 15px; }
        .content { background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #777; border-top: 1px solid #ddd; padding-top: 15px; }
        .highlight { font-weight: bold; color: #9D2449; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #9D2449; border-radius: 4px; font-weight: bold; margin-top: 15px; }
        .text-center { text-align: center; }
        .link-fallback { font-size: 13px; color: #777; margin-top: 25px; word-break: break-all; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('materialpro/assets/images/morenaicons/logos.jpg')) }}" alt="Logos Institucionales">
            <h2 style="margin: 0; color: #333;">Restablecer Contraseña</h2>
        </div>
        
        <p>Estimado(a) <strong>Usuario</strong>,</p>
        
        <p>Se ha recibido una solicitud para restablecer la contraseña de su cuenta en el <strong>Sistema de Gestión de Oficios</strong>.</p>

        <div class="content">
            <p>Cuenta asociada: <span class="highlight">{{ $email }}</span></p>
            <p>Para crear una nueva contraseña, por favor haga clic en el siguiente botón:</p>
            
            <div class="text-center">
                <a href="{{ route('password.reset', $token) }}?email={{ $email }}" class="btn" style="color: #ffffff !important; text-decoration: none;">Restablecer Contraseña</a>
            </div>

            <div class="link-fallback">
                <em>Si el botón no funciona, copie y pegue el siguiente enlace en su navegador web:</em><br><br>
                <a href="{{ route('password.reset', $token) }}?email={{ $email }}" style="color: #9D2449;">
                    {{ route('password.reset', $token) }}?email={{ $email }}
                </a>
            </div>
        </div>

        <p style="font-size: 13px; color: #666; font-style: italic;">
            * Si usted no solicitó este cambio, puede ignorar este correo; su contraseña actual seguirá siendo válida.
        </p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Derechos Reservados. Secretaría de la Contraloría del Estado de México.</p>
        </div>
    </div>
</body>
</html>