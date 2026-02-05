<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        
        .header { background-color: #9D2449; padding: 30px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: normal; text-transform: uppercase; }
        
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .greeting { font-size: 20px; color: #9D2449; margin-bottom: 20px; font-weight: bold; }
        
        .btn-container { text-align: center; margin-top: 30px; margin-bottom: 30px; }
        .btn { 
            background-color: #C09F62; 
            color: #ffffff !important; 
            padding: 14px 28px; 
            text-decoration: none; 
            border-radius: 50px; 
            font-weight: bold; 
            display: inline-block;
            font-size: 16px;
        }
        .btn:hover { background-color: #a88a52; }

        .footer { background-color: #333333; color: #999999; text-align: center; padding: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recuperación de Contraseña</h1>
        </div>

        <div class="content">
            <div class="greeting">Hola,</div>
            
            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta asociada al correo <strong>{{ $email }}</strong>.</p>
            
            <p>Si tú realizaste esta solicitud, haz clic en el siguiente botón para crear una nueva contraseña:</p>
            
            <div class="btn-container">
                <a href="{{ route('password.reset', $token) }}?email={{ $email }}" class="btn">Restablecer Contraseña</a>
            </div>

            <p style="font-size: 13px; color: #777;">
                Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:<br>
                <span style="color: #9D2449; word-break: break-all;">{{ route('password.reset', $token) }}?email={{ $email }}</span>
            </p>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
            
            <p style="font-size: 12px; color: #999;">
                Si no solicitaste este cambio, puedes ignorar este correo de forma segura. Tu contraseña seguirá siendo la misma.
            </p>
        </div>

        <div class="footer">
            <p>Gobierno del Estado de México &copy; {{ date('Y') }}</p>
            <p>Sistema de Control de Oficios</p>
        </div>
    </div>
</body>
</html>