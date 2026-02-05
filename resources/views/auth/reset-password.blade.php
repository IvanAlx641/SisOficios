<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Nueva Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/morena-theme.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        body { background: #f8f9fa; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .card { max-width: 400px; width: 100%; border: none; box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-radius: 12px; }
        .btn-gold { background-color: #C09F62; color: white; border-radius: 50px; width: 100%; padding: 10px; border: none; font-weight: 600; }
        .btn-gold:hover { background-color: #a88a52; color: white; }
    </style>
</head>
<body>
    <div class="card p-4">
        <div class="text-center mb-4">
            <h4 class="fw-bold" style="color: #9D2449;">Crear Nueva Contraseña</h4>
            <small class="text-muted">Introduce tus nuevas credenciales de acceso</small>
        </div>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">CORREO</label>
                <input type="email" name="email" class="form-control bg-light text-muted" value="{{ $email ?? old('email') }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">NUEVA CONTRASEÑA</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="ti ti-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" required autofocus>
                </div>
                
                <div class="form-text text-muted small mt-1">
                    <i class="ti ti-info-circle"></i> Mínimo 8 caracteres.
                </div>
                
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">CONFIRMAR CONTRASEÑA</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="ti ti-lock-check text-muted"></i></span>
                    <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" required>
                </div>
            </div>

            <button type="submit" class="btn-gold">Restablecer Contraseña</button>
        </form>
    </div>
</body>
</html>