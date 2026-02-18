@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card w-100 position-relative overflow-hidden border-0 shadow-sm">
            <div class="card-body px-4 py-3 bg-light">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-bold mb-0 text-guinda">Gestión de sistemas</h4>
                    </div>
                    <div class="col-3 text-end">
                        <a href="{{ route('sistema.create') }}" class="btn btn-guinda w-75 py-2 shadow-sm rounded-pill">
                            Nuevo
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('sistema.index') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-guinda2 small">Nombre del sistema</label>
                            <div class="input-group">
                                <input type="text" name="nombre_sistema" class="form-control border-guinda"
                                    placeholder="Buscar..." value="{{ $request->nombre_sistema }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-guinda2small">Siglas</label>
                            <div class="input-group">
                                <input type="text" name="sigla_sistema" class="form-control border-guinda"
                                    placeholder="Ej. SAM" value="{{ $request->sigla_sistema }}">
                            </div>
                        </div>

                        <div class="col-md-4 end-md-0 text-end">
                            <button type="submit" class="btn btn-outline-guinda w-50 fw-bold">
                                Buscar
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-8 d-flex align-items-center flex-wrap">
                            <label class="form-label fw-bold text-guinda2 me-3 mb-0">Estatus:</label>
                            <div class="btn-group me-4 shadow-sm" role="group">
                                <input type="radio" class="btn-check" name="inactivo" value="Todas" id="st_all"
                                    onchange="this.form.submit()"
                                    {{ $request->inactivo == 'Todas' || !$request->filled('inactivo') ? 'checked' : '' }}>
                                <label class="btn btn-outline-gold" for="st_all">Todos</label>

                                <input type="radio" class="btn-check" name="inactivo" value="Activas" id="st_active"
                                    onchange="this.form.submit()" {{ $request->inactivo == 'Activas' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success-custom" for="st_active">Activos</label>

                                <input type="radio" class="btn-check" name="inactivo" value="Inactivas" id="st_inactive"
                                    onchange="this.form.submit()" {{ $request->inactivo == 'Inactivas' ? 'checked' : '' }}>
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
                                <th class="ps-4 py-3">
                                    <h6 class="fs-4 fw-bold mb-0 text-white">Nombre del sistema</h6>
                                </th>
                                <th class="text-center py-3">
                                    <h6 class="fs-4 fw-bold mb-0 text-white">Siglas</h6>
                                </th>
                                <th class="text-center py-3">
                                    <h6 class="fs-4 fw-bold mb-0 text-white">Eliminar</h6>
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
                                    <td class="text-center">
                                        <span
                                            class="badge badge-guinda-subtle fw-semibold fs-2">{{ $sistema->sigla_sistema }}</span>
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
                <div class="mt-4 px-4 pb-3 d-flex justify-content-end">
                    {!! $sistemas->appends($request->all())->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
