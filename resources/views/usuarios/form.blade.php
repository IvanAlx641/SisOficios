@extends('layouts.admin')

@section('content')

<div class="card border-0 shadow-sm rounded-3">
    
    <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-primary mb-1">
                {{ $usuario->exists ? 'Editar Usuario' : 'Registrar Nuevo Usuario' }}
            </h4>
            <p class="text-muted small mb-0">
                Complete la información requerida. Los campos con <span class="text-danger">*</span> son obligatorios.
            </p>
        </div>
        <a href="{{ route('usuario.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="ti ti-arrow-left me-1"></i> Regresar
        </a>
    </div>

    <div class="card-body p-4">
        
        @if ($usuario->exists)
            <form action="{{ route('usuario.update', $usuario->id) }}" method="POST">
            @method('PUT')
        @else
            <form action="{{ route('usuario.store') }}" method="POST">
        @endif

            @csrf

            <div class="row g-4">
                
                <div class="col-md-6">
                    <label class="form-label fw-bold text-primary small">NOMBRE COMPLETO <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-user text-muted"></i></span>
                        <input type="text" name="nombre" 
                               class="form-control border-start-0 ps-0 @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre', $usuario->nombre) }}" 
                               placeholder="Ej. JUAN PÉREZ GARCÍA" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-primary small">CORREO ELECTRÓNICO <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-mail text-muted"></i></span>
                        <input type="email" name="email" 
                               class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                               value="{{ old('email', $usuario->email) }}" 
                               placeholder="usuario@dominio.gob.mx" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-primary small">ROL DE SISTEMA <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-shield-lock text-muted"></i></span>
                        <select name="rol" class="form-select border-start-0 ps-0 @error('rol') is-invalid @enderror" required>
                            <option value="">Seleccione una opción...</option>
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ old('rol', $usuario->rol) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('rol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-primary small">UNIDAD ADMINISTRATIVA <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-building-arch text-muted"></i></span>
                        <select name="unidad_administrativa_id" class="form-select border-start-0 ps-0 @error('unidad_administrativa_id') is-invalid @enderror" required>
                            <option value="">Seleccione una opción...</option>
                            @foreach($unidades as $id => $nombre)
                                <option value="{{ $id }}" {{ old('unidad_administrativa_id', $usuario->unidad_administrativa_id) == $id ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('unidad_administrativa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if($usuario->exists)
                    <div class="col-12 mt-4">
                        <div class="p-4 bg-light rounded-3 border">
                            <h6 class="text-primary fw-bold mb-3 d-flex align-items-center">
                                <i class="ti ti-key me-2"></i> Actualizar Contraseña (Opcional)
                            </h6>
                            <div class="alert alert-warning py-2 px-3 small border-0 bg-warning-subtle text-warning-emphasis">
                                <i class="ti ti-info-circle me-1"></i> 
                                Solo llena estos campos si deseas <strong>cambiar</strong> la contraseña actual del usuario manualmente. Si los dejas vacíos, se mantendrá la contraseña actual.
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted fw-bold">NUEVA CONTRASEÑA</label>
                                    <input type="password" name="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Mínimo 8 caracteres">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted fw-bold">CONFIRMAR NUEVA CONTRASEÑA</label>
                                    <input type="password" name="password_confirmation" 
                                        class="form-control" 
                                        placeholder="Repite la contraseña">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <div class="d-flex justify-content-end gap-2 mt-5 pt-3 border-top">
                <a href="{{ route('usuario.index') }}" class="btn btn-light border px-4">
                    Cancelar
                </a>
                
                <button type="submit" class="btn btn-gold px-5 rounded-pill shadow-sm">
                    <i class="ti ti-device-floppy me-2"></i>
                    {{ $usuario->exists ? 'Guardar Cambios' : 'Registrar Usuario' }}
                </button>
            </div>

        </form>
    </div>
</div>

@endsection