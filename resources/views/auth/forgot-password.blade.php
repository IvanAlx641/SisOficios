<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña - Secretaría de la Contraloría</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --guinda: #9D2449;
            --guinda-oscuro: #811e3c;
            --dorado: #C09F62;
            --gris-bg: #F8F9FA;
        }

        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: white;
        }

        /* === SECCIÓN IZQUIERDA (IDÉNTICA AL LOGIN) === */
        .login-sidebar {
            position: relative;
            background-color: var(--guinda);
            overflow: hidden;
            background: url("{{ asset('materialpro/assets/images/morenaicons/fondo_login.jpg') }}") no-repeat center center;
            background-size: cover;
            min-height: 100vh;
        }

        .footer-icons-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: var(--guinda);
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            border-top: 5px solid var(--dorado);
            z-index: 10;
        }

        /* --- ESTADO NORMAL --- */
.icon-item { 
    text-align: center; 
    color: white; 
    transition: all 0.3s ease; /* Movimiento suave */
    cursor: default; /* Mantiene la flecha estándar, NO la manita */
}

.icon-circle {
    width: 45px; 
    height: 45px; 
    border: 2px solid var(--dorado); 
    border-radius: 50%;
    display: flex; 
    align-items: center; 
    justify-content: center; 
    margin: 0 auto 8px auto;
    background: rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

/* --- ESTADO HOVER (EFECTO DE FLOTACIÓN) --- */
.icon-item:hover {
    transform: translateY(-8px); /* Eleva el icono 8 píxeles */
}

.icon-item:hover .icon-circle {
    /* Mantiene el diseño pero con una ligera sombra para resaltar el efecto de flotado */
    box-shadow: 0 5px 15px rgba(0,0,0,0.3); 
}

        /* === SECCIÓN DERECHA (ESTRUCTURA ESTÁTICA) === */
        .login-form-wrapper {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            min-height: 100vh;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .img-logos {
            width: 100%;
            max-width: 280px;
            display: block;
            margin: 0 auto 2rem auto;
        }

        .img-titulo {
            width: 100%;
            max-width: 320px;
            display: block;
            margin: 0 auto 0.5rem auto;
        }

        .img-pleca {
            width: 100%;
            max-width: 350px;
            height: auto;
            display: block;
            margin: 0 auto 2rem auto;
            opacity: 0.9;
        }

        .form-label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 5px;
            margin-left: 2px;
        }

        .form-control {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 1rem;
        }

        .form-control:focus {
            background: white;
            border-color: var(--dorado);
            box-shadow: 0 0 0 3px rgba(192, 159, 98, 0.1);
        }

        .btn-guinda {
            background-color: var(--guinda);
            color: white;
            border-radius: 50px;
            padding: 12px 0;
            font-weight: 700;
            width: 100%;
            border: none;
            margin-top: 15px;
            transition: all 0.3s;
        }

        .btn-guinda:hover {
            background-color: var(--guinda-oscuro);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(157, 36, 73, 0.2);
        }

        .forgot-link {
            display: block;
            text-align: left;
            margin-top: 25px;
            color: var(--guinda);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: var(--dorado);
        }

        .mobile-footer {
            height: 10px;
            background: linear-gradient(90deg, var(--guinda) 60%, var(--dorado) 40%);
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        /* Clase para el título "¿Olvidaste tu contraseña?" */
        .auth-header-title {
            color: var(--guinda);
            font-weight: 700;
            margin-bottom: 5px;
        }

        /* Estilo para la alerta de éxito de Material Pro */
        .custom-alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: none;
            border-radius: 12px;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <div class="row g-0">

            <div class="col-lg-8 d-none d-lg-block login-sidebar">
                <div class="footer-icons-bar">
                    <div class="icon-item">
                        <div class="icon-circle"><i class="ti ti-search fs-3"></i></div>
                        <div class="icon-label">Búsqueda</div>
                    </div>
                    <div class="icon-item">
                        <div class="icon-circle"><i class="ti ti-eye fs-3"></i></div>
                        <div class="icon-label">Monitoreo</div>
                    </div>
                    <div class="icon-item">
                        <div class="icon-circle"><i class="ti ti-file-analytics fs-3"></i></div>
                        <div class="icon-label">Seguimiento</div>
                    </div>
                    <div class="icon-item">
                        <div class="icon-circle"><i class="ti ti-clipboard-list fs-3"></i></div>
                        <div class="icon-label">Reporte</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 login-form-wrapper">
                <div class="login-card">

                    <img src="{{ asset('materialpro/assets/images/morenaicons/logos.jpg') }}" class="img-logos"
                        alt="Logos">
                    <img src="{{ asset('materialpro/assets/images/morenaicons/textologin.png') }}" class="img-titulo"
                        alt="Reporte Informativo">
                    <img src="{{ asset('materialpro/assets/images/morenaicons/pleca.png') }}" class="img-pleca"
                        alt="Separador">

                    <div class="text-center mb-4">
                        <h5 class="auth-header-title">¿Olvidaste tu contraseña?</h5>
                        <p class="text-muted small">Ingresa tu correo y te enviaremos las instrucciones.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success custom-alert-success small py-2 mb-3 text-center">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico:</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-guinda">Enviar</button>

                        <div class="mt-4">
                            <a href="{{ route('login') }}" class="forgot-link fw-bold">
                                <i class="ti ti-arrow-left"></i> Volver al inicio de sesión
                            </a>
                        </div>
                    </form>

                </div>
                <div class="mobile-footer d-lg-none"></div>
            </div>

        </div>
    </div>

</body>

</html>
