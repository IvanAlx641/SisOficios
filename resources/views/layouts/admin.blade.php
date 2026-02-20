<!DOCTYPE html>
<html lang="es" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="horizontal">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" type="image/png"
        href="{{ asset('materialpro/assets/images/morenaicons/favicon.png') }}" />

    <link rel="stylesheet" href="{{ asset('materialpro/assets/css/styles.css') }}" />
    <link href="{{ asset('css/morena-theme.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            /* Sobrescribimos el color PRIMARIO de Bootstrap (el azul por defecto) */
            --bs-primary: #9D2449; 
            --bs-primary-rgb: 177, 38, 49; /* Necesario para las opacidades de Bootstrap */
            
            /* Color secundario (Dorado suave) */
            --bs-secondary: #BC955C;
            --bs-secondary-rgb: 188, 149, 92;

            /* Opcional: Ajustar el color de los enlaces */
            --bs-link-color: #9D2449;
            --bs-link-hover-color: #801B24;
        }
        /* --- 1. MEJORA VISUAL (Fuentes) --- */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .logo-custom {
            height: 45px;
            width: auto;
            object-fit: contain;
        }

        /* Ocultar elementos innecesarios */
        .moon,
        .sun,
        .lang-flag {
            display: none !important;
        }

        /* Estilo del Círculo de Usuario */
        .user-initial-circle {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px;
            border-radius: 50% !important;
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1 / 1;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .topbar,
        .app-header {
            border-bottom: none !important;
            box-shadow: none !important;
        }

        /* --- 2. SOLUCIÓN DEFINITIVA AL MENU (Cerrar Sesión) --- */
        .custom-dropdown-pos {
            position: absolute !important;

            /* ALINEACIÓN EXACTA:
               Usamos -10px o 0px dependiendo de tu gusto.
               Si '0' no llega al borde de la imagen, ponle un valor negativo como '-15px'. */
            right: -15px !important;

            left: auto !important;
            top: 100% !important;

            /* TAMAÑO Y ESTÉTICA */
            min-width: 300px !important;
            /* Más ancha para que se vea elegante */
            margin-top: 15px !important;
            /* Separación visual de la barra roja */
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            padding: 0 !important;
            /* Limpieza interna */

            /* ESTABILIDAD (Evita que salte al hacer clic) */
            transform: none !important;
            will-change: transform;
        }

        /* EL PUENTE INVISIBLE (Magia para que no se cierre)
           Crea una zona segura entre el nombre y el cuadro blanco */
        .custom-dropdown-pos::before {
            content: '';
            position: absolute;
            top: -20px;
            /* Cubre el hueco hacia arriba */
            left: 0;
            width: 100%;
            height: 20px;
            background: transparent;
            display: block;
        }

        /* Animación suave de entrada */
        .dropdown-menu.show {
            display: block !important;
            animation: fadeInMenu 0.2s ease-out forwards;
        }

        @keyframes fadeInMenu {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --- 3. MÓVIL (RESPONSIVE) --- */
        .sidebartoggler {
            display: none;
        }

        @media (max-width: 1199px) {
            .sidebartoggler {
                display: block !important;
                color: white !important;
                margin-right: 15px;
                font-size: 1.5rem;
                cursor: pointer;
            }

            .header-container {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            .app-title-text {
                display: none;
            }

            /* Ajuste específico para móvil para que no se salga de la pantalla */
            .custom-dropdown-pos {
                right: -10px !important;
                min-width: 260px !important;
            }
        }

        @media (min-width: 576px) {
            .app-title-text {
                display: block;
            }
        }

        /* Contenedor relativo para posicionar el dropdown */
        .searchable-dropdown-wrapper {
            position: relative;
        }

        /* El input que simula ser el select */
        .searchable-trigger {
            text-align: left;
            background-color: #fff;
            cursor: pointer;
        }

        /* La lista desplegable con el buscador */
        .searchable-menu {
            display: none;
            /* Oculto por defecto */
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 0.5rem;
            margin-top: 0.1rem;
        }

        .searchable-menu.show {
            display: block;
        }

        /* Contenedor de las opciones con scroll */
        .searchable-options {
            max-height: 200px;
            overflow-y: auto;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .searchable-option {
            padding: 0.5rem 1rem;
            cursor: pointer;
            display: block;
            color: #212529;
            text-decoration: none;
        }

        .searchable-option:hover {
            background-color: #f8f9fa;
            color: #1e2125;
        }

        .body-wrapper .container-fluid {
            padding-top: 0 !important;
        }

        /* Si todavía hay espacio, fuerza todo */
        .body-wrapper {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
    </style>

    <title>Sistema de Oficios</title>
</head>

<body>
    <div class="preloader">
        <img src="{{ asset('materialpro/assets/images/morenaicons/Logolayoyt.png') }}" alt="loader"
            class="lds-ripple img-fluid" style="width: 80px;" />
    </div>

    <div id="main-wrapper">
        //- MENU VERTICAL (MÓVIL) -//
        <aside class="left-sidebar with-vertical">
            <div>
                <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                    <ul id="sidebarnav">

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <iconify-icon icon="solar:user-id-line-duotone" class="aside-icon"></iconify-icon>
                                <span class="hide-menu">Administracion</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('usuario.index') }}" class="sidebar-link">
                                        <i class="ti ti-user"></i>
                                        <span class="hide-menu">Usuarios</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('solicitante.index') }}" class="sidebar-link">
                                        <i class="ti ti-file-pencil"></i>
                                        <span class="hide-menu">Solicitantes</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('tiporequerimiento.index') }}" class="sidebar-link">
                                        <i class="ti ti-checklist"></i>
                                        <span class="hide-menu">Tipos de Requerimientos</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('sistema.index') }}" class="sidebar-link">
                                        <i class="ti ti-screen-share"></i>
                                        <span class="hide-menu">Sistemas</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <iconify-icon icon="solar:file-text-linear" class="aside-icon"></iconify-icon>
                                <span class="hide-menu">Oficios</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('oficio.index') }}" class="sidebar-link">
                                        <i class="ti ti-file-plus"></i>
                                        <span class="hide-menu">Registro</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('turno.index') }}" class="sidebar-link">
                                        <i class="ti ti-tournament"></i>
                                        <span class="hide-menu">Turno</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('seguimiento.index') }}" class="sidebar-link">
                                        <i class="ti ti-arrow-guide"></i>
                                        <span class="hide-menu">Seguimiento</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">
                                        <i class="ti ti-file-symlink"></i>
                                        <span class="hide-menu">Respuesta</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">
                                        <i class="ti ti-file-search"></i>
                                        <span class="hide-menu">Buscador</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <iconify-icon icon="solar:file-check-linear" class="aside-icon"></iconify-icon>
                                <span class="hide-menu">Actividades</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">
                                        <i class="ti ti-file-like"></i>
                                        <span class="hide-menu">Bitacora</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <iconify-icon icon="solar:diagram-up-broken" class="aside-icon"></iconify-icon>
                                <span class="hide-menu">Informes</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">
                                        <i class="ti ti-file-analytics"></i>
                                        <span class="hide-menu">Oficios</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">
                                        <i class="ti ti-home-stats"></i>
                                        <span class="hide-menu">Actividades</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="page-wrapper">

            <header class="topbar rounded-0 border-0 bg-morena-primary">
                <div class="app-header border-0 shadow-none">
                    <nav class="navbar navbar-expand-xl container-fluid p-0 px-3 header-container"
                        style="font-family: inherit;">

                        <div class="d-flex align-items-center justify-content-between w-100" style="height: 64px;">

                            <div class="d-flex align-items-center">
                                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse"
                                    href="javascript:void(0)">
                                    <i class="ti ti-menu-2"></i>
                                </a>

                                <a class="text-nowrap logo-img d-flex align-items-center">
                                    <img src="{{ asset('materialpro/assets/images/morenaicons/Logolayoyt.png') }}"
                                        alt="logo" class="logo-custom" style="height: 40px;" />
                                </a>

                                <span class="text-white fw-bold fs-4 ms-3 app-title-text">
                                    Sistema de Oficios
                                </span>

                                <a href="/" class="text-white d-flex align-items-center ms-3 d-none d-sm-flex"
                                    title="Dashboard">
                                    <iconify-icon icon="solar:home-2-linear" width="24"
                                        height="24"></iconify-icon>
                                </a>
                            </div>

                            <div class="d-flex align-items-center">
                                <ul class="navbar-nav">
                                    <li class="nav-item dropdown position-relative">
                                        <a class="nav-link d-flex align-items-center p-0" href="javascript:void(0)"
                                            id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="d-none d-lg-block text-nowrap text-white fw-medium me-3">
                                                {{ Auth::user()->nombre ?? 'Usuario' }}
                                            </span>

                                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 40px; height: 40px; min-width: 40px; flex-shrink: 0; line-height: 0;">
                                                {{ substr(Auth::user()->nombre ?? 'U', 0, 1) }}
                                            </div>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end shadow border-0 custom-dropdown-pos"
                                            aria-labelledby="drop2" style="min-width: 210px;">
                                            <div class="py-3 border-bottom text-center">
                                                <h5 class="mb-1 fs-4 text-dark fw-semibold">
                                                    {{ Auth::user()->nombre ?? 'Usuario' }}</h5>
                                                <p class="mb-0 fs-2 text-muted">{{ Auth::user()->email ?? 'email' }}
                                                </p>
                                            </div>
                                            <div class="p-3">
                                                <form action="{{ route('logout') }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                                                        <i class="ti ti-logout"></i> Cerrar Sesión
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </nav>
                </div>
            </header>

            <aside class="left-sidebar with-horizontal">
                <div>
                    <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
                        <ul id="sidebarnav">

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:user-id-line-duotone" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Administracion</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('usuario.index') }}" class="sidebar-link">
                                            <i class="ti ti-user"></i>
                                            <span class="hide-menu">Usuarios</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('solicitante.index') }}" class="sidebar-link">
                                            <i class="ti ti-file-pencil"></i>
                                            <span class="hide-menu">Solicitantes</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('tiporequerimiento.index') }}" class="sidebar-link">
                                            <i class="ti ti-checklist"></i>
                                            <span class="hide-menu">Tipos de Requerimientos</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('sistema.index') }}" class="sidebar-link">
                                            <i class="ti ti-screen-share"></i>
                                            <span class="hide-menu">Sistemas</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:file-text-linear" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Oficios</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('oficio.index') }}" class="sidebar-link">
                                            <i class="ti ti-file-plus"></i>
                                            <span class="hide-menu">Registro</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('turno.index') }}" class="sidebar-link">
                                            <i class="ti ti-tournament"></i>
                                            <span class="hide-menu">Turno</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('seguimiento.index') }}" class="sidebar-link">
                                            <i class="ti ti-arrow-guide"></i>
                                            <span class="hide-menu">Seguimiento</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-file-symlink"></i>
                                            <span class="hide-menu">Respuesta</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-file-search"></i>
                                            <span class="hide-menu">Buscador</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:file-check-linear" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Actividades</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-file-like"></i>
                                            <span class="hide-menu">Bitacora</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:diagram-up-broken" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Informes</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-file-analytics"></i>
                                            <span class="hide-menu">Oficios</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-home-stats"></i>
                                            <span class="hide-menu">Actividades</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <div class="body-wrapper">
                <div class="container-fluid">

                    <div class="mb-3">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center"
                                role="alert"
                                style="background-color: #d1e7dd; color: #0f5132; border-left: 5px solid #198754 !important;">
                                <i class="ti ti-circle-check fs-4 me-2 text-success"></i>
                                <div><strong>¡Excelente!</strong> {{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center"
                                role="alert"
                                style="background-color: #f8d7da; color: #842029; border-left: 5px solid #dc3545 !important;">
                                <i class="ti ti-alert-triangle fs-4 me-2 text-danger"></i>
                                <div><strong>¡Atención!</strong> {{ session('error') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <div class="dark-transparent sidebartoggler"></div>

    <script src="{{ asset('materialpro/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('materialpro/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('materialpro/assets/libs/simplebar/dist/simplebar.min.js') }}"></script>

    <script src="{{ asset('materialpro/assets/js/theme/app.horizontal.init.js') }}"></script>

    <script src="{{ asset('materialpro/assets/js/theme/theme.js') }}"></script>
    <script src="{{ asset('materialpro/assets/js/theme/app.min.js') }}"></script>
    <script src="{{ asset('materialpro/assets/js/theme/sidebarmenu.js') }}"></script>
    <script src="{{ asset('materialpro/assets/js/theme/feather.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

    @stack('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var alerts = document.querySelectorAll('.alert-success, .alert-danger');
            if (alerts) {
                setTimeout(function() {
                    alerts.forEach(function(alert) {
                        var bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    });
                }, 5000); // 5 SEGUNDOS
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Buscamos específicamente los enlaces del menú vertical (Móvil) que tienen flechita
            var mobileMenuLinks = document.querySelectorAll('.left-sidebar.with-vertical .sidebar-link.has-arrow');

            mobileMenuLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    // Prevenir que navegue a #
                    e.preventDefault();

                    // Seleccionar el submenú (la lista <ul> que sigue al enlace)
                    var nextUl = this.nextElementSibling;

                    if (nextUl && nextUl.tagName === 'UL') {
                        // Verificar si está abierto o cerrado
                        var isOpen = nextUl.style.display === 'block' || nextUl.classList.contains(
                            'show');

                        if (isOpen) {
                            // CERRAR
                            nextUl.style.display = 'none';
                            nextUl.classList.remove('in', 'show');
                            this.classList.remove('active');
                            this.setAttribute('aria-expanded', 'false');
                        } else {
                            // ABRIR
                            nextUl.style.display = 'block';
                            nextUl.classList.add('in', 'show');
                            this.classList.add('active');
                            this.setAttribute('aria-expanded', 'true');
                        }
                    }
                });
            });
        });
    </script>
    <script>
        /**
         * Convierte un SELECT normal en un Dropdown con Buscador.
         * @param {string} idSelect - El ID del select original (ej: 'dependencia_id')
         */
        function convertirSelectABuscador(idSelect) {
            const originalSelect = document.getElementById(idSelect);
            if (!originalSelect) return;

            // 1. Si ya existe un buscador previo para este select, lo borramos para regenerarlo
            // (Esto es útil para cuando se actualiza vía AJAX)
            const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
            if (wrapperPrevio) {
                wrapperPrevio.remove();
                originalSelect.style.display = 'block'; // Reset temporal
            }

            // 2. Crear la estructura HTML del buscador
            const wrapper = document.createElement('div');
            wrapper.className = 'searchable-dropdown-wrapper';

            // Botón que muestra la opción seleccionada
            const trigger = document.createElement('button');
            trigger.className = 'form-select searchable-trigger';
            trigger.type = 'button'; // Importante para no enviar formulario al click
            // Texto inicial: lo que tenga el select seleccionado o el placeholder
            const selectedOption = originalSelect.options[originalSelect.selectedIndex];
            trigger.textContent = selectedOption ? selectedOption.text : 'Seleccione una opción';

            // Menú desplegable
            const menu = document.createElement('div');
            menu.className = 'searchable-menu';

            // Input buscador
            const inputSearch = document.createElement('input');
            inputSearch.className = 'form-control mb-2';
            inputSearch.type = 'text';
            inputSearch.placeholder = 'Buscar...';

            // Lista de opciones container
            const optionsList = document.createElement('div');
            optionsList.className = 'searchable-options';

            // 3. Llenar la lista basándonos en el SELECT original
            function poblarOpciones() {
                optionsList.innerHTML = ''; // Limpiar
                Array.from(originalSelect.options).forEach(option => {
                    // Omitir la opción vacía de "Seleccione..." si no tiene valor
                    if (option.value === "") return;

                    const item = document.createElement('div');
                    item.className = 'searchable-option';
                    item.textContent = option.text;
                    item.dataset.value = option.value;

                    // Al hacer click en una opción
                    item.addEventListener('click', () => {
                        // Actualizar el SELECT original (OCULTO)
                        originalSelect.value = option.value;

                        // Disparar evento 'change' en el select original (CRUCIAL para tu AJAX)
                        originalSelect.dispatchEvent(new Event('change'));

                        // Actualizar visualmente el botón
                        trigger.textContent = option.text;

                        // Cerrar menú
                        menu.classList.remove('show');
                        inputSearch.value = ''; // Reset buscador
                        filtrarOpciones(''); // Reset filtro
                    });

                    optionsList.appendChild(item);
                });
            }
            poblarOpciones();

            // 4. Lógica de Filtrado
            function filtrarOpciones(texto) {
                const items = optionsList.querySelectorAll('.searchable-option');
                const filtro = texto.toLowerCase();
                items.forEach(item => {
                    const coincide = item.textContent.toLowerCase().includes(filtro);
                    item.style.display = coincide ? 'block' : 'none';
                });
            }

            inputSearch.addEventListener('keyup', (e) => {
                filtrarOpciones(e.target.value);
            });

            // 5. Mostrar/Ocultar Menú
            trigger.addEventListener('click', (e) => {
                // Cerrar otros menús abiertos si los hubiera
                document.querySelectorAll('.searchable-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('show');
                });
                menu.classList.toggle('show');
                if (menu.classList.contains('show')) inputSearch.focus();
            });

            // Cerrar al hacer click fuera
            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) {
                    menu.classList.remove('show');
                }
            });

            // 6. Ensamblaje en el DOM
            menu.appendChild(inputSearch);
            menu.appendChild(optionsList);
            wrapper.appendChild(trigger);
            wrapper.appendChild(menu);

            // Insertar el wrapper después del select y ocultar el select original
            originalSelect.parentNode.insertBefore(wrapper, originalSelect.nextSibling);
            originalSelect.style.display = 'none';
        }
    </script>
</body>

</html>
