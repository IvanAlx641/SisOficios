<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - Secretaría de la Contraloría</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --guinda: #9D2449;
            /* Color Primario */
            --guinda-oscuro: #811e3c;
            --dorado: #C09F62;
            /* Color Secundario */
            --gris-bg: #F8F9FA;
        }

        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: white;
        }

        /* === SECCIÓN IZQUIERDA (IMAGEN) === */
        .login-sidebar {
            position: relative;
            background-color: var(--guinda);
            overflow: hidden;
            /* IMAGEN DE FONDO */
            background: url("{{ asset('materialpro/assets/images/morenaicons/fondo_login.jpg') }}") no-repeat center center;
            background-size: cover;
            min-height: 100vh;
        }

        /* Barra Inferior Roja (Recreada con CSS para nitidez) */
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
            /* La línea dorada superior */
            z-index: 10;
        }

        .icon-item {
            text-align: center;
            color: white;
            transition: 0.3s;
            cursor: default;
        }

        .icon-item:hover {
            transform: translateY(-3px);
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
        }

        .icon-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }


        /* === SECCIÓN DERECHA (FORMULARIO) === */
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

        /* 1. Logos Superiores */
        .img-logos {
            width: 100%;
            max-width: 280px;
            display: block;
            margin: 0 auto 2rem auto;
            /* Centrado */
        }

        /* 2. Texto "REPORTE INFORMATIVO" */
        .img-titulo {
            width: 100%;
            max-width: 320px;
            display: block;
            margin: 0 auto 0.5rem auto;
        }

        /* 3. Pleca Dorada */
        .img-pleca {
            width: 100%;
            max-width: 350px;
            height: auto;
            display: block;
            margin: 0 auto 2rem auto;
            opacity: 0.9;
        }

        /* Inputs */
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
            /* Más redondeado */
            padding: 12px 15px;
            font-size: 1rem;
        }

        .form-control:focus {
            background: white;
            border-color: var(--dorado);
            box-shadow: 0 0 0 3px rgba(192, 159, 98, 0.1);
        }

        /* Botón */
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

        /* Footer móvil */
        .mobile-footer {
            height: 10px;
            background: linear-gradient(90deg, var(--guinda) 60%, var(--dorado) 40%);
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
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

                    <img src="{{ asset('materialpro/assets/images/morenaicons/logos.jpg') }}"
                        alt="Gobierno del Estado de México" class="img-logos">

                    <img src="{{ asset('materialpro/assets/images/morenaicons/textologin.png') }}"
                        alt="Reporte Informativo" class="img-titulo">

                    <img src="{{ asset('materialpro/assets/images/morenaicons/pleca.png') }}" alt="Separador"
                        class="img-pleca">

                    @if (session('success'))
                        <div class="alert alert-success custom-alert-success small py-2 mb-3 text-center"
                            role="alert">
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico:</label>
                            <div>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña:</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror ">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                        <button type="submit" class="btn btn-guinda">Entrar</button>

                        <div class="mt-4">
                            <p class="text-muted small mb-0">¿No recuerdas tu contraseña?</p>
                            <a href="{{ route('password.request') }}" class="forgot-link fw-bold mt-1">Recupérala
                                aquí.</a>
                        </div>
                    </form>

                </div>

                <div class="mobile-footer d-lg-none"></div>
            </div>

        </div>
    </div>

</body>

</html>
