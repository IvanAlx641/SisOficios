@extends('layouts.admin')

@section('content')

<style>
    :root {
        --gold-color: #C09F62;
        --gold-hover: #A88A52;
        --guinda-color: #9D2449; /* Color Guinda Institucional */
        --guinda-hover: #801d3a;
    }

    /* --- CLASES DE COLOR INSTITUCIONAL --- */
    .text-guinda { color: var(--guinda-color) !important; }
    .bg-guinda { background-color: var(--guinda-color) !important; color: white !important; }
    .border-guinda { border-color: var(--guinda-color) !important; }

    /* Botón Guinda Sólido */
    .btn-guinda {
        background-color: var(--guinda-color);
        border-color: var(--guinda-color);
        color: white;
    }
    .btn-guinda:hover {
        background-color: var(--guinda-hover);
        border-color: var(--guinda-hover);
        color: white;
    }

    /* Botón Guinda Borde (Outline) - Para reemplazar al primary-subtle */
    .btn-outline-guinda {
        color: var(--guinda-color);
        border-color: var(--guinda-color);
        background-color: transparent;
    }
    .btn-outline-guinda:hover {
        background-color: var(--guinda-color);
        color: white;
    }

    /* Badge Guinda (Fondo suave) */
    .badge-guinda-subtle {
        background-color: rgba(157, 36, 73, 0.1) !important; /* Guinda al 10% */
        color: var(--guinda-color) !important;
        border: 1px solid rgba(157, 36, 73, 0.2);
    }

    /* Focus de Inputs (Sombra Guinda) */
    .form-control:focus, .form-select:focus {
        border-color: var(--guinda-color);
        box-shadow: 0 0 0 0.25rem rgba(157, 36, 73, 0.25);
    }

    /* --- ESTILOS DORADOS (GOLD) --- */
    .btn-outline-gold {
        color: var(--gold-color);
        border-color: var(--gold-color);
    }
    .btn-outline-gold:hover, 
    .btn-check:checked + .btn-outline-gold {
        background-color: var(--gold-color);
        border-color: var(--gold-color);
        color: white;
    }
    .btn-gold {
        background-color: var(--gold-color);
        border-color: var(--gold-color);
        color: white;
    }
    .btn-gold:hover {
        background-color: var(--gold-hover);
        border-color: var(--gold-hover);
        color: white;
    }
    .text-gold { color: var(--gold-color) !important; }

    /* --- FILTROS DE ESTATUS --- */
    .btn-check:checked + .btn-outline-success-custom {
        background-color: #28a745; border-color: #28a745; color: white;
    }
    .btn-outline-success-custom { color: #28a745; border-color: #28a745; }
    .btn-outline-success-custom:hover { background-color: #28a745; color: white; }

    .btn-check:checked + .btn-outline-danger-custom {
        background-color: #dc3545; border-color: #dc3545; color: white;
    }
    .btn-outline-danger-custom { color: #dc3545; border-color: #dc3545; }
    .btn-outline-danger-custom:hover { background-color: #dc3545; color: white; }

    /* --- OTROS --- */
    .status-dot {
        width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 8px;
    }
    .dot-active { background-color: #28a745; box-shadow: 0 0 4px #28a745; }
    .dot-inactive { background-color: #dc3545; box-shadow: 0 0 4px #dc3545; }

    .name-link {
        color: #2a3547; text-decoration: none; transition: all 0.2s;
    }
    .name-link:hover {
        color: var(--guinda-color) !important; font-weight: bold;
    }

    .action-disabled {
        color: #adb5bd !important; pointer-events: none; opacity: 0.5;
    }
</style>

<div class="container-fluid">
    
    <div class="card shadow-none position-relative overflow-hidden mb-4 border-0" style="background-color: #f8f9fa;">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-bold mb-0 text-guinda">Gestión de usuarios</h4> 
                </div>
            
                <div class="col-3 text-end">
                    <a href="{{ route('usuario.create') }}" class="btn btn-guinda w-75 py-2 shadow-sm rounded-pill">
                        <i class="ti ti-plus me-1"></i> Agregar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card w-100 position-relative overflow-hidden border-0 shadow-sm">
        <div class="card-body p-4">
            
            <form action="{{ route('usuario.index') }}" method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-guinda small">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-guinda border-guinda"><i class="ti ti-search"></i></span>
                            <input type="text" name="nombre" class="form-control border-guinda" placeholder="Buscar por nombre..." value="{{ $request->nombre }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-guinda small">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-guinda border-guinda"><i class="ti ti-mail"></i></span>
                            <input type="text" name="email" class="form-control border-guinda" placeholder="Buscar por correo..." value="{{ $request->email }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-guinda small">Rol</label>
                        <select class="form-select border-guinda" name="rol" onchange="this.form.submit()">
                            <option value="Todos">Todos los roles</option>
                            <option value="Administrador TI" {{ $request->rol == 'Administrador TI' ? 'selected' : '' }}>Administrador TI</option>
                            <option value="Titular de área" {{ $request->rol == 'Titular de área' ? 'selected' : '' }}>Titular de área</option>
                            <option value="Capturista" {{ $request->rol == 'Capturista' ? 'selected' : '' }}>Capturista</option>
                            <option value="Responsable" {{ $request->rol == 'Responsable' ? 'selected' : '' }}>Responsable</option>
                            <option value="Analista" {{ $request->rol == 'Analista' ? 'selected' : '' }}>Analista</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-guinda w-100 fw-bold">
                            <i class="ti ti-filter me-1"></i> Buscar
                        </button>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-8 d-flex align-items-center flex-wrap">
                        <label class="form-label fw-bold text-guinda me-3 mb-0">Estatus:</label>
                        
                        <div class="btn-group me-4 shadow-sm" role="group">
                            <input type="radio" class="btn-check" name="inactivo" value="Todas" id="st_all" 
                                onchange="this.form.submit()" {{ ($request->inactivo == 'Todas' || !$request->filled('inactivo')) ? 'checked' : '' }}>
                            <label class="btn btn-outline-gold" for="st_all">Todos</label>

                            <input type="radio" class="btn-check" name="inactivo" value="Activas" id="st_active" 
                                onchange="this.form.submit()" {{ $request->inactivo == 'Activas' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success-custom" for="st_active">Activos</label>

                            <input type="radio" class="btn-check" name="inactivo" value="Inactivas" id="st_inactive" 
                                onchange="this.form.submit()" {{ $request->inactivo == 'Inactivas' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger-custom" for="st_inactive">Inactivos</label>
                        </div>

                        <div class="d-flex align-items-center gap-3 border-start ps-3 border-secondary-subtle">
                            <div class="d-flex align-items-center"><span class="status-dot dot-active"></span> <small class="text-muted fw-semibold">Activo</small></div>
                            <div class="d-flex align-items-center"><span class="status-dot dot-inactive"></span> <small class="text-muted fw-semibold">Inactivo</small></div>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>

    <div class="card w-100 position-relative overflow-hidden border-0 shadow-sm mt-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-guinda text-white">
                        <tr>
                            <th class="ps-4 py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Nombre</h6></th>
                            <th class="py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Correo electrónico</h6></th>
                            <th class="py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Rol</h6></th>
                            <th class="text-center py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Envío de cuenta</h6></th>
                            <th class="text-center py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Desactivar</h6></th>
                            <th class="text-center py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Eliminar</h6></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <span class="status-dot {{ $usuario->inactivo == 'X' ? 'dot-inactive' : 'dot-active' }}" 
                                        title="{{ $usuario->inactivo == 'X' ? 'Inactivo' : 'Activo' }}">
                                    </span>
                                    
                                    <a href="{{ route('usuario.edit', $usuario->id) }}" class="fs-4 fw-bold mb-0 name-link">
                                        {{ $usuario->nombre }}
                                    </a>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-dark fs-5 mb-1">{{ $usuario->email }}</span>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        @if($usuario->email_verified_at)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2">Verificado</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2">Pendiente</span>
                                        @endif

                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            {{ $usuario->fecha_creacion ? $usuario->fecha_creacion->format('d/m/Y H:i') : 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge badge-guinda-subtle fw-semibold fs-2">
                                    {{ $usuario->rol }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if($usuario->inactivo != 'X')
                                    <form action="{{ route('usuario.notificacion', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Enviar credenciales/recuperación?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-gold border-0 bg-transparent" data-bs-toggle="tooltip" title="Enviar Credenciales">
                                            <i class="ti ti-mail-forward fs-5"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn border-0 bg-transparent action-disabled" disabled>
                                        <i class="ti ti-mail-forward fs-5"></i>
                                    </button>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($usuario->inactivo != 'X')
                                    <form action="{{ route('usuario.desactivar', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Desactivar usuario?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-danger border-0 bg-transparent text-danger" data-bs-toggle="tooltip" title="Desactivar cuenta">
                                            <i class="ti ti-user-off fs-5"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn border-0 bg-transparent action-disabled" disabled title="Usuario Inactivo">
                                        <i class="ti ti-user-off fs-5"></i>
                                    </button>
                                @endif
                            </td>

                            <td class="text-center">
                                <form action="{{ route('usuario.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn border-0 bg-transparent text-guinda" data-bs-toggle="tooltip" title="Eliminar Permanentemente">
                                        <i class="ti ti-trash fs-5"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="ti ti-search fs-1 text-muted mb-2 d-block"></i>
                                <div class="text-muted">No se encontraron resultados para la búsqueda.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 px-4 pb-3 d-flex justify-content-end">
                {!! $usuarios->appends($request->all())->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection