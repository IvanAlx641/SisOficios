<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Configurar Contraseña - Primer Ingreso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        body { 
            background-color: #f8f9fa; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Segoe UI', sans-serif;
        }
        .card { 
            max-width: 450px; 
            width: 100%; 
            border: none; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); 
            overflow: hidden;
            border-radius: 12px;
        }
        .header { 
            background-color: #9D2449; 
            color: white; 
            padding: 25px 20px; 
            text-align: center; 
        }
        .text-guinda { color: #9D2449; }
        
        .btn-gold { 
            background-color: #C09F62; 
            color: white; 
            width: 100%; 
            border-radius: 50px; 
            padding: 12px; 
            border: none; 
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-gold:hover { 
            background-color: #a88a52; 
            color: white; 
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h4 class="mb-1 fw-bold">Bienvenido</h4>
            <div style="font-size: 0.9rem; opacity: 0.9;">Configuración Inicial de Seguridad</div>
        </div>
        
        <div class="card-body p-4 pt-5">
            <div class="alert alert-warning border-0 bg-warning-subtle text-warning-emphasis small mb-4">
                <i class="ti ti-info-circle me-1"></i> 
                Por seguridad, al ser tu primer ingreso, debes establecer una contraseña personal.
            </div>
            
            <form action="{{ route('password.update_initial') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label text-guinda fw-bold small">NUEVA CONTRASEÑA</label>
                    <input type="password" name="password" 
                        class="form-control form-control-lg fs-6 @error('password') is-invalid @enderror" 
                        placeholder="Escribe tu nueva contraseña" required autofocus>
                    
                    <div class="form-text text-muted small mt-1">
                        <i class="ti ti-lock"></i> Mínimo 8 caracteres.
                    </div>

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-guinda fw-bold small">CONFIRMAR CONTRASEÑA</label>
                    <input type="password" name="password_confirmation" 
                        class="form-control form-control-lg fs-6" 
                        placeholder="Repite la contraseña" required>
                </div>

                <button type="submit" class="btn-gold shadow-sm">
                    Guardar y Acceder
                </button>
            </form>
            
            <div class="text-center mt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted text-decoration-none small" style="font-size: 0.85rem;">
                        Cancelar y Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>