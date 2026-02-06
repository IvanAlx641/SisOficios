@extends('layouts.admin')

@section('content')

<style>
    :root {
        --gold-color: #C09F62;
        --gold-hover: #A88A52;
        --guinda-color: #9D2449;
    }

    .btn-outline-gold { color: var(--gold-color); border-color: var(--gold-color); }
    .btn-outline-gold:hover, .btn-check:checked + .btn-outline-gold {
        background-color: var(--gold-color); border-color: var(--gold-color); color: white;
    }

    .btn-outline-success-custom { color: #28a745; border-color: #28a745; }
    .btn-check:checked + .btn-outline-success-custom { background-color: #28a745; color: white; }

    .btn-outline-danger-custom { color: #dc3545; border-color: #dc3545; }
    .btn-check:checked + .btn-outline-danger-custom { background-color: #dc3545; color: white; }

    .status-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 8px; }
    .dot-active { background-color: #28a745; box-shadow: 0 0 4px #28a745; }
    .dot-inactive { background-color: #dc3545; box-shadow: 0 0 4px #dc3545; }

    .name-link { color: #2a3547; text-decoration: none; transition: all 0.2s; }
    .name-link:hover { color: var(--guinda-color) !important; font-weight: bold; }

    .action-disabled { color: #adb5bd !important; pointer-events: none; opacity: 0.5; }
    .text-guinda { color: var(--guinda-color) !important; }
</style>

<div class="container-fluid">
    <div class="card bg-primary-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="row align-items-center p-3">
            <div class="col-9">
                <h4 class="fw-semibold mb-0">Gestión de Solicitantes</h4>
            </div>
            <div class="col-3 text-end">
                <a href="{{ route('solicitantes.create') }}" class="btn btn-primary py-2">
                    <i class="ti ti-plus me-1"></i> Agregar
                </a>
            </div>
        </div>
    </div>

    <div class="card w-100 position-relative overflow-hidden">
        <div class="card-body p-4 border-bottom">
            <form action="{{ route('solicitantes.index') }}" method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-primary small">Nombre del Solicitante</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-primary border-primary"><i class="ti ti-search"></i></span>
                            <input type="text" name="nombre" class="form-control border-primary" value="{{ request('nombre') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-primary small">Estatus</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="inactivo" value="Todas" id="st_all" onchange="this.form.submit()" {{ (request('inactivo') == 'Todas' || !request('inactivo')) ? 'checked' : '' }}>
                            <label class="btn btn-outline-gold" for="st_all small">Todos</label>

                            <input type="radio" class="btn-check" name="inactivo" value="Activas" id="st_active" onchange="this.form.submit()" {{ request('inactivo') == 'Activas' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success-custom" for="st_active small">Activos</label>

                            <input type="radio" class="btn-check" name="inactivo" value="Inactivas" id="st_inactive" onchange="this.form.submit()" {{ request('inactivo') == 'Inactivas' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger-custom" for="st_inactive small">Inactivos</label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary-subtle text-primary w-100">
                            <i class="ti ti-filter me-1"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive rounded-2">
            <table class="table border text-nowrap customize-table mb-0 align-middle">
                <thead class="text-white bg-primary">
                    <tr>
                        <th><h6 class="fs-4 fw-semibold mb-0 text-white">Nombre</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0 text-white">Unidad Administrativa</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0 text-white">Cargo</h6></th>
                        <th class="text-center"><h6 class="fs-4 fw-semibold mb-0 text-white">Acciones</h6></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($solicitantes as $sol)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="status-dot {{ $sol->inactivo == 'X' ? 'dot-inactive' : 'dot-active' }}"></span>
                                <a href="{{ route('solicitantes.edit', $sol->id) }}" class="fs-4 fw-semibold mb-0 name-link">
                                    {{ $sol->nombre }}
                                </a>
                            </div>
                        </td>
                        <td><span class="fs-3">{{ $unidades[$sol->unidad_administrativa_id] ?? 'N/A' }}</span></td>
                        <td><span class="badge bg-primary-subtle text-primary fw-semibold fs-2">{{ $sol->cargo }}</span></td>
                        <td class="text-center">
                            <div class="dropdown dropstart">
                                <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item d-flex align-items-center gap-3" href="{{ route('solicitantes.edit', $sol->id) }}"><i class="fs-4 ti ti-edit"></i>Editar</a></li>
                                    <li>
                                        <form action="{{ route('solicitantes.destroy', $sol->id) }}" method="POST" onsubmit="return confirm('¿Eliminar?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item d-flex align-items-center gap-3 text-danger"><i class="fs-4 ti ti-trash"></i>Eliminar</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-5 text-muted">No se encontraron resultados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $solicitantes->links() }}</div>
    </div>
</div>
@endsection