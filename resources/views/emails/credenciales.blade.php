<!DOCTYPE html>
<html>
<head>
    <style>
        /* Estilos en línea para asegurar compatibilidad con Gmail/Outlook */
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        
        /* Encabezado Guinda */
        .header { background-color: #9D2449; padding: 30px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: normal; letter-spacing: 1px; }
        
        /* Contenido */
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .greeting { font-size: 20px; color: #9D2449; margin-bottom: 20px; font-weight: bold; }
        
        /* Caja Dorada para las credenciales */
        .credentials-box { 
            background-color: #fdfbf7; 
            border: 1px solid #C09F62; 
            border-left: 5px solid #C09F62; 
            padding: 20px; 
            margin: 25px 0; 
            border-radius: 4px;
        }
        .credentials-box p { margin: 5px 0; font-size: 16px; }
        .label { color: #888; font-size: 14px; text-transform: uppercase; font-weight: bold; }
        .value { color: #333; font-weight: bold; font-family: monospace; font-size: 18px; }

        /* Botón */
        .btn-container { text-align: center; margin-top: 30px; }
        .btn { 
            background-color: #9D2449; 
            color: #ffffff !important; 
            padding: 12px 25px; 
            text-decoration: none; 
            border-radius: 50px; 
            font-weight: bold; 
            display: inline-block;
        }
        .btn:hover { background-color: #7a1c38; }

        /* Pie de página */
        .footer { background-color: #333333; color: #999999; text-align: center; padding: 20px; font-size: 12px; }
        .footer p { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Inicio de sesión</h1>
        </div>

        <div class="content">
            <div class="greeting">Hola, {{ $usuario->nombre }}</div>
            
            <p>Bienvenido al <strong>Sistema de Control de Oficios</strong>. Tu cuenta ha sido creada</p>
            <p>Estas son tus credenciales temporales para ingresar:</p>
            
            <div class="credentials-box">
                <p><span class="label">Correo Electrónico:</span><br>
                <span class="value">{{ $usuario->email }}</span></p>
                <br>
                <p><span class="label">Contraseña Temporal:</span><br>
                <span class="value" style="color: #9D2449; font-size: 20px;">{{ $password }}</span></p>
            </div>

            <p style="font-size: 13px; color: #666;">
                * Por seguridad, el sistema te pedirá cambiar esta contraseña inmediatamente después de iniciar sesión.
            </p>
            
            <div class="btn-container">
                <a href="{{ route('login') }}" class="btn">Ingresar al Sistema</a>
            </div>
        </div>

        <div class="footer">
            <p>Gobierno del Estado de México &copy; {{ date('Y') }}</p>
            <p>Este es un correo automático, favor de no responder.</p>
        </div>
    </div>
</body>
</html>