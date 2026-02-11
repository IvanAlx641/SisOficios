@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card w-100 position-relative overflow-hidden border-0 shadow-sm">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-bold mb-0 text-guinda">Tipos de requerimientos</h4>
                    </div>

                    <div class="col-3 text-end">
                        <a href="{{ route('tiporequerimiento.create') }}"
                            class="btn btn-guinda w-75 py-2 shadow-sm rounded-pill">
                            <i class="ti ti-plus me-1"></i> Agregar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">

                <form action="{{ route('tiporequerimiento.index') }}" method="GET">
                    <div class="row g-3 align-items-end">

                        <div class="col-md-9">
                            <label class="form-label fw-bold text-guinda small">Tipo de requerimiento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-guinda border-guinda"><i
                                        class="ti ti-search"></i></span>
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
                                    onchange="this.form.submit()"
                                    {{ $request->inactivo == 'Todas' || !$request->filled('inactivo') ? 'checked' : '' }}>
                                <label class="btn btn-outline-gold" for="st_all">Todos</label>

                                <input type="radio" class="btn-check" name="inactivo" value="Activos" id="st_active"
                                    onchange="this.form.submit()" {{ $request->inactivo == 'Activos' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success-custom" for="st_active">Activos</label>

                                <input type="radio" class="btn-check" name="inactivo" value="Inactivos" id="st_inactive"
                                    onchange="this.form.submit()" {{ $request->inactivo == 'Inactivos' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger-custom" for="st_inactive">Inactivos</label>
                            </div>

                            <div class="d-flex align-items-center gap-3 border-start ps-3 border-secondary-subtle">
                                <div class="d-flex align-items-center"><span class="status-dot dot-active"></span> <small
                                        class="text-muted fw-semibold">Activo</small></div>
                                <div class="d-flex align-items-center"><span class="status-dot dot-inactive"></span> <small
                                        class="text-muted fw-semibold">Inactivo</small></div>
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
                                <th class="text-center ps-4 py-3">
                                    <h6 class="fs-4 fw-bold mb-0 text-white">Tipo de requerimiento</h6>
                                </th>
                                <th class="text-center py-3">
                                    <h6 class="fs-4 fw-bold mb-0 text-white">Aplica oficios</h6>
                                </th>
                                <th class="text-center py-3">
                                    <h6 class="fs-4 fw-bold mb-0 text-white">Aplica actividades</h6>
                                </th>
                                <th class="text-center py-3">
                                    <h6 class="fs-4 fw-bold mb-0 text-white">Eliminar</h6>
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
                                                class="fs-4 fw-bold mb-0 name-link">
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

                <div class="mt-4 px-4 pb-3 d-flex justify-content-end">
                    {!! $tiposrequerimientos->appends($request->all())->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
