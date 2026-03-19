<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configurar contraseña - Secretaría de la Contraloría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --guinda: #9D2449;
            --dorado: #C09F62;
            --dorado-oscuro: #a88a52;
        }

        body, html {
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: white;
            overflow: hidden; /* Cero scroll */
        }

        /* === COLUMNA IZQUIERDA (IDÉNTICA AL LOGIN) === */
        .login-sidebar {
            position: relative;
            background: url("{{ asset('materialpro/assets/images/morenaicons/fondo_login.jpg') }}") no-repeat center center;
            background-size: cover;
            height: 100vh;
        }

        .footer-icons-bar {
            position: absolute; bottom: 0; left: 0; width: 100%; height: 100px;
            background-color: var(--guinda); display: flex; align-items: center;
            justify-content: center; gap: 2rem; border-top: 5px solid var(--dorado);
        }
        
        .icon-item { text-align: center; color: white; transition: all 0.3s ease; cursor: default; }
        .icon-item:hover { transform: translateY(-8px); }
        .icon-circle {
            width: 45px; height: 45px; border: 2px solid var(--dorado); border-radius: 50%;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 8px auto;
            background: rgba(0, 0, 0, 0.1); transition: all 0.3s ease;
        }
        .icon-item:hover .icon-circle { box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .icon-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 500; }

        /* === COLUMNA DERECHA (ESTRUCTURA DE LOGIN) === */
        .login-form-wrapper {
            background: white; display: flex; align-items: center; justify-content: center;
            height: 100vh;
        }
        .login-card { width: 100%; max-width: 420px; padding: 20px; }

        /* Imágenes fijas (Posición milimétrica) */
        .img-logos { width: 100%; max-width: 280px; display: block; margin: 0 auto 1.5rem auto; }
        .img-titulo { width: 100%; max-width: 320px; display: block; margin: 0 auto 0.5rem auto; }
        .img-pleca { width: 100%; max-width: 350px; height: auto; display: block; margin: 0 auto 1.5rem auto; opacity: 0.9; }

        /* Estilo Formulario */
        .auth-header { text-align: center; margin-bottom: 1rem; }
        .auth-title { color: var(--guinda); font-weight: 700; margin-bottom: 2px; }
        
        .alert-mini {
            background-color: #fff8e1; border-left: 4px solid #ffc107; color: #856404;
            font-size: 0.8rem; padding: 10px; border-radius: 4px; margin-bottom: 1.5rem;
        }

        .form-label-custom { color: gray; font-weight: 700; font-size: 0.8rem; margin-bottom: 5px; display: block; }
        .form-control { border-radius: 12px; padding: 12px; border: 1px solid #e0e0e0; }
        
        .btn-guinda-action {
            background-color: var(--guinda); color: white; border-radius: 50px; padding: 12px 0;
            font-weight: 700; width: 100%; border: none; margin-top: 10px; transition: 0.3s;
        }
        .btn-gold-action:hover { background-color: var(--dorado-oscuro); color: white; transform: translateY(-1px); }
        
        .btn-cancel { background: none; border: none; color: #6c757d; font-size: 0.85rem; font-weight: 600; text-decoration: none; }
        .btn-cancel:hover { color: var(--guinda); }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-lg-8 d-none d-lg-block login-sidebar">
            <div class="footer-icons-bar">
                <div class="icon-item"><div class="icon-circle"><i class="ti ti-search fs-3"></i></div><div class="icon-label">Búsqueda</div></div>
                <div class="icon-item"><div class="icon-circle"><i class="ti ti-eye fs-3"></i></div><div class="icon-label">Monitoreo</div></div>
                <div class="icon-item"><div class="icon-circle"><i class="ti ti-file-analytics fs-3"></i></div><div class="icon-label">Seguimiento</div></div>
                <div class="icon-item"><div class="icon-circle"><i class="ti ti-clipboard-list fs-3"></i></div><div class="icon-label">Reporte</div></div>
            </div>
        </div>

        <div class="col-lg-4 login-form-wrapper">
            <div class="login-card">
                <img src="{{ asset('materialpro/assets/images/morenaicons/logos.jpg') }}" class="img-logos" alt="Logos">
                <img src="{{ asset('materialpro/assets/images/morenaicons/textologin.png') }}" class="img-titulo" alt="Titulo">
                <img src="{{ asset('materialpro/assets/images/morenaicons/pleca.png') }}" class="img-pleca" alt="Pleca">

                <div class="auth-header">
                    <p class="text-muted small">Configuración inicial</p>
                </div>

                <div class="alert alert-mini">
                    <i class="ti ti-info-circle me-1"></i> Por seguridad, debes establecer una contraseña personal.
                </div>

                <form action="{{ route('password.update_initial') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label-custom">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Escribe tu nueva contraseña" required autofocus>
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la contraseña" required>
                    </div>

                    <button type="submit" class="btn-guinda-action">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>