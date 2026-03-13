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
                width: auto !important;
                /* Evita que ocupe todo el espacio en pantallas pequeñas */
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
        }
    </style>

    <div class="container-fluid px-2 px-md-3">

        {{-- 2. AJUSTE HTML: Quité 'overflow-hidden' de esta clase para que el menú se vea --}}
        <div class="card w-100 position-relative border-0 shadow-sm mb-3">
            <div class="card-body pt-2 py-3 bg-light">
                <div class="row align-items-center">
                    <div class="col-7 col-md-9">
                        <h4 class="fw-bold mb-0 text-guinda">Gestión de usuarios</h4>
                    </div>

                    <div class="col-5 col-md-3 text-end">
                        <a href="{{ route('usuario.create') }}"
                            class="btn btn-guinda w-75  py-2 shadow-sm rounded-pill btn-nuevo-responsive">
                            Nuevo
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-3 p-md-4">

                <form action="{{ route('usuario.index') }}" method="GET" id="filterForm">
                    <div class="row g-3 align-items-end">

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-bold text-guinda2 mb-0">Nombre:</label>
                            <div class="input-group mt-1 mt-md-0">
                                <input type="text" name="nombre" class="form-control border-guinda"
                                    placeholder="Buscar por nombre..." value="{{ $request->nombre }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-bold text-guinda2 mb-0">Correo electrónico:</label>
                            <div class="input-group mt-1 mt-md-0">
                                <input type="text" name="email" class="form-control border-guinda"
                                    placeholder="Buscar por correo..." value="{{ $request->email }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-bold text-guinda2 mb-0">Rol:</label>
                            {{-- 3. AJUSTE HTML: Agregué id="filtro_rol" --}}
                            <select class="form-select border-guinda mt-1 mt-md-0" name="rol" id="filtro_rol"
                                onchange="this.form.submit()">
                                <option value="Todos">Todos los roles</option>
                                <option value="Administrador TI"
                                    {{ $request->rol == 'Administrador TI' ? 'selected' : '' }}>Administrador TI</option>
                                <option value="Titular de área" {{ $request->rol == 'Titular de área' ? 'selected' : '' }}>
                                    Titular de área</option>
                                <option value="Capturista" {{ $request->rol == 'Capturista' ? 'selected' : '' }}>Capturista
                                </option>
                                <option value="Responsable" {{ $request->rol == 'Responsable' ? 'selected' : '' }}>
                                    Responsable</option>
                                <option value="Analista" {{ $request->rol == 'Analista' ? 'selected' : '' }}>Analista
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-2 text-md-end mt-3 mt-md-0">
                            <button type="submit" class="btn btn-outline-guinda w-100 w-md-50 fw-bold ">
                                Buscar
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 d-flex flex-column flex-md-row align-items-md-center gap-2 gap-md-0">
                            <label class="form-label fw-bold text-guinda2 me-md-3 mb-0">Estatus:</label>

                            <div class="d-flex flex-column flex-md-row align-items-md-center w-100">
                                <div class="d-flex w-100 w-md-auto me-md-4 mb-2 mb-md-0"
                                    style="overflow-x: auto; padding-bottom: 2px;">
                                    <div class="btn-group shadow-sm" role="group">
                                        <input type="radio" class="btn-check" name="inactivo" value="Todas"
                                            id="st_all" onchange="this.form.submit()"
                                            {{ $request->inactivo == 'Todas' || !$request->filled('inactivo') ? 'checked' : '' }}>
                                        <label class="btn btn-outline-gold badge-filtro-btn text-nowrap py-2"
                                            for="st_all">Todos</label>

                                        <input type="radio" class="btn-check" name="inactivo" value="Activas"
                                            id="st_active" onchange="this.form.submit()"
                                            {{ $request->inactivo == 'Activas' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success-custom badge-filtro-btn text-nowrap py-2"
                                            for="st_active">Activos</label>

                                        <input type="radio" class="btn-check" name="inactivo" value="Inactivas"
                                            id="st_inactive" onchange="this.form.submit()"
                                            {{ $request->inactivo == 'Inactivas' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger-custom badge-filtro-btn text-nowrap py-2"
                                            for="st_inactive">Inactivos</label>
                                    </div>
                                    <div
                                    class="d-flex align-items-center gap-3 ps-md-3 border-md-start border-secondary-subtle">
                                    <div class="d-flex align-items-center"><span class="status-dot dot-active me-1"></span>
                                        <small class="text-muted fw-semibold">Activo</small></div>
                                    <div class="d-flex align-items-center"><span
                                            class="status-dot dot-inactive me-1"></span> <small
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
                    <table class="table table-hover mb-0 align-middle" style="min-width: 800px;">
                        <thead class="bg-guinda text-white">
                            <tr>
                                <th class="text-left ps-4 py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Nombre</h6>
                                </th>
                                <th class="text-left py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Correo electrónico</h6>
                                </th>
                                <th class="text-left py-3 text-nowrap">
                                    <h6 class="text-white text-left form-label fw-bold small mb-0">Rol</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Envío de cuenta</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Desactivar</h6>
                                </th>
                                <th class="text-center py-3 text-nowrap">
                                    <h6 class="text-white text-center form-label fw-bold small mb-0">Eliminar</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usuarios as $usuario)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold mb-1 fs-3 link-oficio-gris text-left">
                                            <span
                                                class="status-dot {{ $usuario->inactivo == 'X' ? 'dot-inactive' : 'dot-active' }}"
                                                title="{{ $usuario->inactivo == 'X' ? 'Inactivo' : 'Activo' }}">
                                            </span>

                                            <a href="{{ route('usuario.edit', $usuario->id) }}"
                                                class="fw-bold mb-1 fs-3 link-oficio-gris">
                                                {{ $usuario->nombre }}
                                            </a>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-wrap small text-left">{{ $usuario->email }}</span>

                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                @if ($usuario->email_verified_at)
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-1 py-0"
                                                        style="font-size: 0.7rem;">Verificado el:</span>
                                                @else
                                                    <span
                                                        class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-1 py-0"
                                                        style="font-size: 0.7rem;">Pendiente</span>
                                                @endif

                                                <small class="text-muted text-wrap small">
                                                    {{ $usuario->fecha_creacion ? $usuario->fecha_creacion->format('d/m/Y H:i') : 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="text-wrap small text-left">
                                            {{ $usuario->rol }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if ($usuario->inactivo != 'X')
                                            <form action="{{ route('usuario.notificacion', $usuario->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Enviar credenciales/recuperación?');">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-outline-guinda border-0 bg-transparent"
                                                    data-bs-toggle="tooltip" title="Enviar Credenciales">
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
                                        @if ($usuario->inactivo != 'X')
                                            <form action="{{ route('usuario.desactivar', $usuario->id) }}" method="POST"
                                                onsubmit="return confirm('¿Desactivar usuario?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="btn btn-outline-danger border-0 bg-transparent text-guinda"
                                                    data-bs-toggle="tooltip" title="Desactivar cuenta">
                                                    <i class="ti ti-user-off fs-5"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn border-0 bg-transparent action-disabled" disabled
                                                title="Usuario Inactivo">
                                                <i class="ti ti-user-off fs-5"></i>
                                            </button>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('usuario.destroy', $usuario->id) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar definitivamente este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn border-0 bg-transparent text-guinda"
                                                data-bs-toggle="tooltip" title="Eliminar Permanentemente">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="ti ti-search fs-1 text-muted mb-2 d-block"></i>
                                        <div class="text-muted small">No se encontraron resultados para la búsqueda.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 px-3 px-md-4 pb-3 d-flex justify-content-center justify-content-md-end">
                    {!! $usuarios->appends($request->all())->links() !!}
                </div>
            </div>
        </div>
    </div>
    {{-- 4. SCRIPT (JS) --}}
    <script>
        function convertirSelectABuscador(idSelect) {
            const originalSelect = document.getElementById(idSelect);
            if (!originalSelect) return;

            const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
            if (wrapperPrevio) wrapperPrevio.remove();

            const wrapper = document.createElement('div');
            wrapper.className = 'searchable-dropdown-wrapper';

            const trigger = document.createElement('button');
            trigger.className = 'form-select searchable-trigger border-guinda';
            trigger.type = 'button';

            const selectedOption = originalSelect.options[originalSelect.selectedIndex];
            trigger.textContent = selectedOption ? selectedOption.text : 'Seleccione una opción';

            const menu = document.createElement('div');
            menu.className = 'searchable-menu';

            const inputSearch = document.createElement('input');
            inputSearch.className = 'form-control mb-2';
            inputSearch.type = 'text';
            inputSearch.placeholder = 'Buscar...';
            inputSearch.onclick = function(e) {
                e.stopPropagation();
            };

            const optionsList = document.createElement('div');
            optionsList.className = 'searchable-options';

            function poblarOpciones() {
                optionsList.innerHTML = '';
                Array.from(originalSelect.options).forEach(option => {
                    const item = document.createElement('div');
                    item.className = 'searchable-option';
                    item.textContent = option.text;

                    item.addEventListener('click', () => {
                        originalSelect.value = option.value;
                        trigger.textContent = option.text;
                        menu.classList.remove('show');
                        inputSearch.value = '';
                        filtrarOpciones('');
                        originalSelect.dispatchEvent(new Event('change'));
                    });
                    optionsList.appendChild(item);
                });
            }
            poblarOpciones();

            function filtrarOpciones(texto) {
                const items = optionsList.querySelectorAll('.searchable-option');
                const filtro = texto.toLowerCase();
                items.forEach(item => {
                    const coincide = item.textContent.toLowerCase().includes(filtro);
                    item.style.display = coincide ? 'block' : 'none';
                });
            }

            inputSearch.addEventListener('keyup', (e) => filtrarOpciones(e.target.value));

            trigger.addEventListener('click', (e) => {
                document.querySelectorAll('.searchable-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('show');
                });
                menu.classList.toggle('show');
                if (menu.classList.contains('show')) setTimeout(() => inputSearch.focus(), 100);
            });

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) menu.classList.remove('show');
            });

            menu.appendChild(inputSearch);
            menu.appendChild(optionsList);
            wrapper.appendChild(trigger);
            wrapper.appendChild(menu);

            originalSelect.parentNode.insertBefore(wrapper, originalSelect.nextSibling);
            originalSelect.style.display = 'none';
        }

        // INICIALIZACIÓN PARA EL CAMPO DE ROL
        document.addEventListener("DOMContentLoaded", function() {
            convertirSelectABuscador('filtro_rol');
        });
    </script>
@endsection
