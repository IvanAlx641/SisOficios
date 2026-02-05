@extends('layouts.admin')

@section('content')

<style>
    :root {
        --gold-color: #C09F62;
        --gold-hover: #A88A52;
    }

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

    .text-gold {
        color: var(--gold-color) !important;
    }
</style>

<div class="container-fluid">
    <div class="card bg-primary-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="col-9">
            <h4 class="fw-semibold mb-8">Gestión de Usuarios</h4>
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
                        <label class="form-label fw-semibold text-primary">Correo Electrónico</label>
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

                    <div class="col-md-3 text-end">
                        <a href="{{ route('usuario.create') }}" class="btn btn-primary w-100 py-2">
                            <i class="ti ti-plus me-1"></i> Nuevo Usuario
                        </a>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 d-flex align-items-center">
                        <label class="form-label fw-semibold text-gold me-3 mb-0">Estatus:</label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="inactivo" value="Todas" id="st_all" 
                                   onchange="this.form.submit()" {{ ($request->inactivo == 'Todas' || !$request->filled('inactivo')) ? 'checked' : '' }}>
                            <label class="btn btn-outline-gold" for="st_all">Todos</label>

                            <input type="radio" class="btn-check" name="inactivo" value="Activas" id="st_active" 
                                   onchange="this.form.submit()" {{ $request->inactivo == 'Activas' ? 'checked' : '' }}>
                            <label class="btn btn-outline-gold" for="st_active">Activos</label>

                            <input type="radio" class="btn-check" name="inactivo" value="Inactivas" id="st_inactive" 
                                   onchange="this.form.submit()" {{ $request->inactivo == 'Inactivas' ? 'checked' : '' }}>
                            <label class="btn btn-outline-gold" for="st_inactive">Inactivos</label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-primary-subtle text-primary">
                            <i class="ti ti-filter me-1"></i> Aplicar Filtros
                        </button>
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
                            <th class="text-center"><h6 class="fs-4 fw-semibold mb-0 text-white">Estatus/Editar</h6></th>
                            <th class="text-center"><h6 class="fs-4 fw-semibold mb-0 text-white">Eliminar</h6></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle text-primary rounded-circle round-40 d-flex align-items-center justify-content-center fw-bold me-3">
                                        {{ substr($usuario->nombre, 0, 1) }}
                                    </div>
                                    <h6 class="fs-4 fw-semibold mb-0 text-dark">{{ $usuario->nombre }}</h6>
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
                                            {{ $usuario->fecha_creacion ? $usuario->fecha_creacion->format('d/m/Y H:i:s') : 'N/A' }}
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
                                    <form action="{{ route('usuario.notificacion', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Generar nueva contraseña y enviarla por correo?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-gold border-0 bg-transparent" data-bs-toggle="tooltip" title="Enviar Credenciales">
                                            <i class="ti ti-mail-forward fs-6"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    @if($usuario->inactivo != 'X')
                                        <a href="{{ route('usuario.edit', $usuario->id) }}" class="btn btn-outline-primary border-0 bg-transparent text-primary" data-bs-toggle="tooltip" title="Editar">
                                            <i class="ti ti-pencil fs-6"></i>
                                        </a>

                                        <form action="{{ route('usuario.desactivar', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Desactivar usuario?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-outline-primary border-0 bg-transparent text-primary" data-bs-toggle="tooltip" title="Inactivar cuenta">
                                                <i class="ti ti-user-off fs-6"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('usuario.reactivar', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Reactivar este usuario?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-gold rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;" data-bs-toggle="tooltip" title="Reactivar">
                                                <i class="ti ti-power fs-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                            <td class="text-center">
                                @if($usuario->inactivo == 'X')
                                    <form action="{{ route('usuario.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-primary border-0 bg-transparent text-danger" data-bs-toggle="tooltip" title="Eliminar">
                                            <i class="ti ti-trash fs-6"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted opacity-25"><i class="ti ti-trash fs-6"></i></span>
                                @endif
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