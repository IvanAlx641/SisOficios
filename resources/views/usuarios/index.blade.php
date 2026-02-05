@extends('layouts.admin')

@section('content')

<style>
    :root {
        --gold-color: #C09F62;
        --gold-hover: #A88A52;
        --guinda-color: #9D2449; /* Color Guinda Institucional */
    }

    /* --- TUS ESTILOS ORIGINALES --- */
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

    .btn-ginda {
        background-color: var(-guinda-color);
        border-color: var(-guinda-color);
        color: white;
    }

    .btn-gold:hover {
        background-color: var(--gold-hover);
        border-color: var(--gold-hover);
        color: white;
    }

    .text-gold {
        color: var(--gold-color) !important;
    }

    /* --- NUEVOS ESTILOS PARA LOS CAMBIOS SOLICITADOS --- */

    /* 1. Filtros de Estatus con Colores */
    .btn-check:checked + .btn-outline-success-custom {
        background-color: #28a745; border-color: #28a745; color: white;
    }
    .btn-outline-success-custom {
        color: #28a745; border-color: #28a745;
    }
    .btn-outline-success-custom:hover {
        background-color: #28a745; color: white;
    }

    .btn-check:checked + .btn-outline-danger-custom {
        background-color: #dc3545; border-color: #dc3545; color: white;
    }
    .btn-outline-danger-custom {
        color: #dc3545; border-color: #dc3545;
    }
    .btn-outline-danger-custom:hover {
        background-color: #dc3545; color: white;
    }

    /* 2. Puntos de Estatus (Dots) */
    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px; /* Separación pequeña con el texto */
    }
    .dot-active { background-color: #28a745; box-shadow: 0 0 4px #28a745; }
    .dot-inactive { background-color: #dc3545; box-shadow: 0 0 4px #dc3545; }

    /* 3. Nombre con Link y Hover Guinda */
    .name-link {
        color: #2a3547; /* Color oscuro original */
        text-decoration: none;
        transition: all 0.2s;
    }
    .name-link:hover {
        color: var(--guinda-color) !important;
        font-weight: bold;
    }

    /* 4. Botones Deshabilitados (Grises) */
    .action-disabled {
        color: #adb5bd !important; /* Gris */
        pointer-events: none;
        opacity: 0.5;
    }

    /* 5. Color Guinda para Eliminar */
    .text-guinda {
        color: var(--guinda-color) !important;
    }
</style>

<div class="container-fluid">
    <div class="card bg-primary-subtle shadow-none position-relative overflow-hidden mb-4">
        <div> 
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-0">Gestión de usuarios</h4>
                </div>
            
                <div class="col-3 text-end">
                    <a href="{{ route('usuario.create') }}" class="btn btn-primary w-50 py-2">
                        <i class="ti ti-plus me-1"></i> Agregar
                    </a>
                </div>
            
            </div>
        </div>
    </div>

    <div class="card w-100 position-relative overflow-hidden">
        <div class="card-body p-4">
            <form action="{{ route('usuario.index') }}" method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-primary">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-primary border-primary"><i class="ti ti-search"></i></span>
                            <input type="text" name="nombre" class="form-control border-primary" placeholder="Buscar por nombre..." value="{{ $request->nombre }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-primary">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-primary border-primary"><i class="ti ti-mail"></i></span>
                            <input type="text" name="email" class="form-control border-primary" placeholder="Buscar por correo..." value="{{ $request->email }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-primary">Rol</label>
                        <select class="form-select border-primary" name="rol" onchange="this.form.submit()">
                            <option value="Todos">Todos los roles</option>
                            <option value="Administrador TI" {{ $request->rol == 'Administrador TI' ? 'selected' : '' }}>Administrador TI</option>
                            <option value="Titular de área" {{ $request->rol == 'Titular de área' ? 'selected' : '' }}>Titular de área</option>
                            <option value="Capturista" {{ $request->rol == 'Capturista' ? 'selected' : '' }}>Capturista</option>
                            <option value="Responsable" {{ $request->rol == 'Responsable' ? 'selected' : '' }}>Responsable</option>
                            <option value="Analista" {{ $request->rol == 'Analista' ? 'selected' : '' }}>Analista</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary-subtle text-primary">
                            <i class="ti ti-filter me-1"></i> Buscar
                        </button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-8 d-flex align-items-center flex-wrap">
                        <label class="form-label fw-semibold text-primary me-3 mb-0">Estatus:</label>
                        
                        <div class="btn-group me-4" role="group">
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

    <div class="card w-100 position-relative overflow-hidden">
        <div class="card-body p-4">
            
            <div class="table-responsive rounded-2">
                <table class="table border text-nowrap customize-table mb-0 align-middle">
                    <thead class="text-white bg-primary">
                        <tr>
                            <th><h6 class="fs-4 fw-semibold mb-0 text-white">Nombre</h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0 text-white">Correo electrónico</h6></th>
                            <th><h6 class="fs-4 fw-semibold mb-0 text-white">Rol</h6></th>
                            <th class="text-center"><h6 class="fs-4 fw-semibold mb-0 text-white">Envío de cuenta</h6></th>
                            <th class="text-center"><h6 class="fs-4 fw-semibold mb-0 text-white">Desactivar</h6></th>
                            <th class="text-center"><h6 class="fs-4 fw-semibold mb-0 text-white">Eliminar</h6></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="status-dot {{ $usuario->inactivo == 'X' ? 'dot-inactive' : 'dot-active' }}" 
                                        title="{{ $usuario->inactivo == 'X' ? 'Inactivo' : 'Activo' }}">
                                    </span>
                                    
                                    <a href="{{ route('usuario.edit', $usuario->id) }}" class="fs-4 fw-semibold mb-0 name-link">
                                        {{ $usuario->nombre }}
                                    </a>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-dark fs-3 mb-1">{{ $usuario->email }}</span>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        @if($usuario->email_verified_at)
                                            <span class="badge bg-success-subtle text-success fs-1 px-2 py-1">Verificado</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning fs-1 px-2 py-1">Pendiente</span>
                                        @endif

                                        <small class="text-muted fs-2">
                                            {{ $usuario->fecha_creacion ? $usuario->fecha_creacion->format('d/m/Y H:i') : 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-semibold fs-2">
                                    {{ $usuario->rol }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if($usuario->inactivo != 'X')
                                    <form action="{{ route('usuario.notificacion', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Enviar credenciales/recuperación?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-gold border-0 bg-transparent" data-bs-toggle="tooltip" title="Enviar Credenciales">
                                            <i class="ti ti-mail-forward fs-6"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn border-0 bg-transparent action-disabled" disabled>
                                        <i class="ti ti-mail-forward fs-6"></i>
                                    </button>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($usuario->inactivo != 'X')
                                    <form action="{{ route('usuario.desactivar', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Desactivar usuario?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-danger border-0 bg-transparent text-danger" data-bs-toggle="tooltip" title="Desactivar cuenta">
                                            <i class="ti ti-user-off fs-6"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn border-0 bg-transparent action-disabled" disabled title="Usuario Inactivo">
                                        <i class="ti ti-user-off fs-6"></i>
                                    </button>
                                @endif
                            </td>

                            <td class="text-center">
                                <form action="{{ route('usuario.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn border-0 bg-transparent text-guinda" data-bs-toggle="tooltip" title="Eliminar Permanentemente">
                                        <i class="ti ti-trash fs-6"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">No se encontraron resultados.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 d-flex justify-content-end">
                {!! $usuarios->appends($request->all())->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection