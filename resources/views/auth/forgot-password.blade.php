<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/morena-theme.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        body { background: #f8f9fa; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: 'Segoe UI', sans-serif;}
        .card { max-width: 400px; width: 100%; border: none; box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-radius: 12px; }
        .btn-guinda { background-color: #9D2449; color: white; border-radius: 50px; width: 100%; padding: 10px; border: none; font-weight: 600; }
        .btn-guinda:hover { background-color: #811e3c; color: white; }
    </style>
</head>
<body>
    <div class="card p-4">
        <div class="text-center mb-4">
            <h5 class="fw-bold" style="color: #9D2449;">¿Olvidaste tu contraseña?</h5>
            <p class="text-muted small">Ingresa tu correo y te enviaremos las instrucciones.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success small border-0 bg-success-subtle text-success mb-3">
                <i class="ti ti-check"></i> {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">CORREO ELECTRÓNICO</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="ti ti-mail text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" placeholder="ejemplo@correo.com" required>
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn-guinda">Enviar Enlace</button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-decoration-none small text-muted">
                <i class="ti ti-arrow-left"></i> Volver al inicio de sesión
            </a>
        </div>
    </div>
</body>
</html>