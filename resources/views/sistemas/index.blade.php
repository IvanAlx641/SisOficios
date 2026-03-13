@extends('layouts.admin')

@section('content')
<style>
    /* Ajustes responsivos exclusivos para celulares (no afectan la vista en PC) */
    @media (max-width: 767.98px) {
        .badge-filtro-btn {
            font-size: 0.7rem !important;
            padding: 0.3rem 0.5rem !important;
        }
        .btn-nuevo-responsive {
            font-size: 0.85rem !important;
            padding-top: 0.4rem !important;
            padding-bottom: 0.4rem !important;
            width: auto !important; /* Evita que ocupe todo el espacio en pantallas pequeñas */
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }
    }
</style>

    <div class="container-fluid px-2 px-md-3">
        <div class="card w-100 position-relative overflow-hidden border-0 shadow-sm">
            <div class="card-body px-3 px-md-4 py-3 bg-light">
                <div class="row align-items-center">
                    <div class="col-7 col-md-9">
                        <h4 class="fw-bold mb-0 text-guinda">Gestión de sistemas</h4>
                    </div>
                    <div class="col-5 col-md-3 text-end">
                        <a href="{{ route('sistema.create') }}" class="btn btn-guinda w-75 py-2 shadow-sm rounded-pill btn-nuevo-responsive">
                            Nuevo
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-3 p-md-4">
                <form action="{{ route('sistema.index') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold text-guinda2 small">Nombre del sistema:</label>
                            <div class="input-group">
                                <input type="text" name="nombre_sistema" class="form-control border-guinda"
                                    placeholder="Buscar..." value="{{ $request->nombre_sistema }}">
                            </div>  
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold text-guinda2 small">Siglas:</label>
                            <div class="input-group">
                                <input type="text" name="sigla_sistema" class="form-control border-guinda"
                                    placeholder="Ej. SAM" value="{{ $request->sigla_sistema }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-2 text-md-end mt-3 mt-md-0">
                            <button type="submit" class="btn btn-outline-guinda w-100 w-md-50 fw-bold">
                                Buscar
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 d-flex flex-column flex-md-row align-items-md-center gap-2 gap-md-0">
                            <label class="form-label fw-bold text-guinda2 me-md-3 mb-0">Estatus:</label>
                            
                            <div class="d-flex flex-column flex-md-row align-items-md-center w-100">
                                <div class="d-flex w-100 w-md-auto me-md-4 mb-2 mb-md-0" style="overflow-x: auto; padding-bottom: 2px;">
                                    <div class="btn-group shadow-sm" role="group">
                                        <input type="radio" class="btn-check" name="inactivo" value="Todas" id="st_all"
                                            onchange="this.form.submit()"
                                            {{ $request->inactivo == 'Todas' || !$request->filled('inactivo') ? 'checked' : '' }}>
                                        <label class="btn btn-outline-gold badge-filtro-btn text-nowrap py-2" for="st_all">Todos</label>

                                        <input type="radio" class="btn-check" name="inactivo" value="Activas" id="st_active"
                                            onchange="this.form.submit()" {{ $request->inactivo == 'Activas' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success-custom badge-filtro-btn text-nowrap py-2" for="st_active">Activos</label>

                                        <input type="radio" class="btn-check" name="inactivo" value="Inactivas" id="st_inactive"
                                            onchange="this.form.submit()" {{ $request->inactivo == 'Inactivas' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger-custom badge-filtro-btn text-nowrap py-2" for="st_inactive">Inactivos</label>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 ps-md-3 border-md-start border-secondary-subtle">
                                    <div class="d-flex align-items-center"><span class="status-dot dot-active me-1"></span> <small
                                            class="text-muted fw-semibold">Activo</small></div>
                                    <div class="d-flex align-items-center"><span class="status-dot dot-inactive me-1"></span> <small
                                            class="text-muted fw-semibold">Inactivo</small></div>
                                </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card w-100 position-relative overflow-hidden border-0 shadow-sm mt-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" style="min-width: 600px;">
                        <thead class="bg-guinda text-white">
                            <tr>
                                <th class="ps-4 py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Nombre del sistema</h6>
                                </th>
                                <th class="text-left py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Siglas</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Eliminar</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sistemas as $sistema)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="status-dot {{ $sistema->inactivo == 'X' ? 'dot-inactive' : 'dot-active' }}"></span>
                                            <a href="{{ route('sistema.edit', $sistema->id) }}" class="fw-bold mb-1 fs-3 link-oficio-gris">
                                                {{ $sistema->nombre_sistema }}
                                            </a>

                                        </div>
                                    </td>
                                    <td class="text-left">
                                        <span
                                            class="text-wrap small">{{ $sistema->sigla_sistema }}</span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('sistema.destroy', $sistema->id) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar sistema?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn border-0 bg-transparent text-guinda">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">No hay resultados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 px-3 px-md-4 pb-3 d-flex justify-content-center justify-content-md-end">
                    {!! $sistemas->appends($request->all())->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection