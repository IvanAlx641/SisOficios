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

    /* Botón Guinda Borde (Outline) */
    .btn-outline-guinda {
        color: var(--guinda-color);
        border-color: var(--guinda-color);
        background-color: transparent;
    }
    .btn-outline-guinda:hover {
        background-color: var(--guinda-color);
        color: white;
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
</style>

<div class="container-fluid">
    
    <div class="card shadow-none position-relative overflow-hidden mb-4 border-0" style="background-color: #f8f9fa;">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-bold mb-0 text-guinda">Tipos de Requerimientos</h4>
                </div>
            
                <div class="col-3 text-end">
                    <a href="{{ route('tiporequerimiento.create') }}" class="btn btn-guinda w-75 py-2 shadow-sm rounded-pill">
                        <i class="ti ti-plus me-1"></i> Agregar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card w-100 position-relative overflow-hidden border-0 shadow-sm">
        <div class="card-body p-4">
            
            <form action="{{ route('tiporequerimiento.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-9">
                        <label class="form-label fw-bold text-guinda small">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-guinda border-guinda"><i class="ti ti-search"></i></span>
                            <input type="text" name="tipo_requerimiento" class="form-control border-guinda" 
                                   placeholder="Buscar..." value="{{ $request->tipo_requerimiento }}">
                        </div>
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

                            <input type="radio" class="btn-check" name="inactivo" value="Activos" id="st_active" 
                                   onchange="this.form.submit()" {{ $request->inactivo == 'Activos' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success-custom" for="st_active">Activos</label>

                            <input type="radio" class="btn-check" name="inactivo" value="Inactivos" id="st_inactive" 
                                   onchange="this.form.submit()" {{ $request->inactivo == 'Inactivos' ? 'checked' : '' }}>
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
                            <th class="ps-4 py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Tipo de Requerimiento</h6></th>
                            <th class="text-center py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Aplica Oficios</h6></th>
                            <th class="text-center py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Aplica Actividades</h6></th>
                            <th class="text-center py-3"><h6 class="fs-4 fw-bold mb-0 text-white">Eliminar</h6></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tiposrequerimientos as $tipo)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <span class="status-dot {{ $tipo->inactivo == 'X' ? 'dot-inactive' : 'dot-active' }}" 
                                          title="{{ $tipo->inactivo == 'X' ? 'Inactivo' : 'Activo' }}">
                                    </span>
                                    <a href="{{ route('tiporequerimiento.edit', $tipo->id) }}" class="fs-4 fw-bold mb-0 name-link">
                                        {{ $tipo->tipo_requerimiento }}
                                    </a>
                                </div>
                            </td>

                            <td class="text-center">
                                @if($tipo->requerimiento_oficio == 'X')
                                    <i class="ti ti-check text-success fs-5 fw-bold"></i>
                                @else
                                    <i class="ti ti-minus text-muted fs-5 opacity-25"></i>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($tipo->requerimiento_actividad == 'X')
                                    <i class="ti ti-check text-success fs-5 fw-bold"></i>
                                @else
                                    <i class="ti ti-minus text-muted fs-5 opacity-25"></i>
                                @endif
                            </td>

                            <td class="text-center">
                                <form action="{{ route('tiporequerimiento.destroy', $tipo->id) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente este registro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn border-0 bg-transparent text-guinda" data-bs-toggle="tooltip" title="Eliminar">
                                        <i class="ti ti-trash fs-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="ti ti-search fs-1 text-muted mb-2 d-block"></i>
                                <div class="text-muted">No se encontraron resultados.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 px-4 pb-3 d-flex justify-content-end">
                {!! $tiposrequerimientos->appends($request->all())->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection