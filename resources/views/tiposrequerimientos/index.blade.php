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
                        <h4 class="fw-bold mb-0 text-guinda">Tipos de requerimientos</h4>
                    </div>

                    <div class="col-5 col-md-3 text-end">
                        <a href="{{ route('tiporequerimiento.create') }}"
                            class="btn btn-guinda w-75  py-2 shadow-sm rounded-pill btn-nuevo-responsive">
                            Nuevo
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-3 p-md-4">

                <form action="{{ route('tiporequerimiento.index') }}" method="GET">
                    <div class="row g-3 align-items-end">

                        <div class="col-12 col-md-9">
                            <label class="form-label fw-bold text-guinda2 small">Tipo de requerimiento: </label>
                            <div class="input-group">
                                <input type="text" name="tipo_requerimiento" class="form-control border-guinda"
                                    placeholder="Buscar..." value="{{ $request->tipo_requerimiento }}">
                            </div>
                        </div>

                        <div class=" col-12 col-md-2 text-md-end mt-3 mt-md-0">
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

                                        <input type="radio" class="btn-check" name="inactivo" value="Activos" id="st_active"
                                            onchange="this.form.submit()" {{ $request->inactivo == 'Activos' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success-custom badge-filtro-btn text-nowrap py-2" for="st_active">Activos</label>

                                        <input type="radio" class="btn-check" name="inactivo" value="Inactivos" id="st_inactive"
                                            onchange="this.form.submit()" {{ $request->inactivo == 'Inactivos' ? 'checked' : '' }}>
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
                                <th class="text-left ps-4 py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Tipo de requerimiento</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Aplica oficios</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Aplica actividades</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Eliminar</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tiposrequerimientos as $tipo)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="status-dot {{ $tipo->inactivo == 'X' ? 'dot-inactive' : 'dot-active' }}"
                                                title="{{ $tipo->inactivo == 'X' ? 'Inactivo' : 'Activo' }}">
                                            </span>
                                            <a href="{{ route('tiporequerimiento.edit', $tipo->id) }}"
                                                class="fw-bold mb-1 fs-3 link-oficio-gris">
                                                {{ $tipo->tipo_requerimiento }}
                                            </a>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        @if ($tipo->requerimiento_oficio == 'X')
                                            <i class="ti ti-check text-success fs-5 fw-bold"></i>
                                        @else
                                            <i class="ti ti-minus text-muted fs-5 opacity-25"></i>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if ($tipo->requerimiento_actividad == 'X')
                                            <i class="ti ti-check text-success fs-5 fw-bold"></i>
                                        @else
                                            <i class="ti ti-minus text-muted fs-5 opacity-25"></i>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('tiporequerimiento.destroy', $tipo->id) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar definitivamente este registro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn border-0 bg-transparent text-guinda"
                                                data-bs-toggle="tooltip" title="Eliminar">
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

                <div class="mt-4 px-3 px-md-4 pb-3 d-flex justify-content-center justify-content-md-end">
                    {!! $tiposrequerimientos->appends($request->all())->links() !!}
                </div>
            </div>
        </div>
</div>
@endsection
