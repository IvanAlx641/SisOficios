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
            --bs-primary: #9D2449; 
            --bs-primary-rgb: 177, 38, 49; 
            --bs-secondary: #BC955C;
            --bs-secondary-rgb: 188, 149, 92;
            --bs-link-color: #9D2449;
            --bs-link-hover-color: #801B24;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .logo-custom {
            height: 45px;
            width: auto;
            object-fit: contain;
        }

        .moon, .sun, .lang-flag {
            display: none !important;
        }

        .topbar, .app-header {
            border-bottom: none !important;
            box-shadow: none !important;
        }

        .sidebartoggler { display: none; }

        @media (max-width: 1199px) {
            .sidebartoggler {
                display: block !important;
                color: white !important;
                margin-right: 15px;
                font-size: 1.5rem;
                cursor: pointer;
            }
            .app-title-text { display: none; }
        }

        @media (min-width: 576px) {
            .app-title-text { display: block; }
        }

        /* --- TAMAÑO DE LETRA REDUCIDO (1rem) --- */
        .app-title-text, 
        .user-name-text {
            font-family: inherit;
            font-size: 1rem !important; 
            font-weight: 600 !important;   
            letter-spacing: 0.5px;
        }

        /* --- ALINEACIÓN PERFECTA DE CONTENEDORES --- */
        .header-container, 
        #sidebarnavh, 
        .body-wrapper .container-fluid {
            padding-left: 15px !important; 
            padding-right: 15px !important;
            max-width: 100% !important;
            margin-left: auto;
            margin-right: auto;
        }

        /* BUSCADOR PERSONALIZADO JS */
        .searchable-dropdown-wrapper { position: relative; }
        .searchable-trigger { text-align: left; background-color: #fff; cursor: pointer; }
        .searchable-menu {
            display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 1000;
            background: #fff; border: 1px solid #dee2e6; border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); padding: 0.5rem; margin-top: 0.1rem;
        }
        .searchable-menu.show { display: block; }
        .searchable-options { max-height: 200px; overflow-y: auto; list-style: none; padding: 0; margin: 0; }
        .searchable-option { padding: 0.5rem 1rem; cursor: pointer; display: block; color: #212529; text-decoration: none; }
        .searchable-option:hover { background-color: #f8f9fa; color: #1e2125; }

        .body-wrapper .container-fluid { padding-top: 0 !important; }
        .body-wrapper { margin-top: 0 !important; padding-top: 0 !important; }
    </style>

    <title>Sistema de Oficios</title>
</head>

<body>
    <div class="preloader">
        <img src="{{ asset('materialpro/assets/images/morenaicons/Logolayoyt.png') }}" alt="loader"
            class="lds-ripple img-fluid" style="width: 80px;" />
    </div>

    <div id="main-wrapper">
        
        <aside class="left-sidebar with-vertical">
            <div>
                <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                    <ul id="sidebarnav">

                        @if(in_array(Auth::user()->rol, ['Administrador TI', 'Titular de área', 'Capturista']))
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <iconify-icon icon="solar:user-id-line-duotone" class="aside-icon"></iconify-icon>
                                <span class="hide-menu">Administración</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @if(in_array(Auth::user()->rol, ['Administrador TI', 'Titular de área']))
                                <li class="sidebar-item">
                                    <a href="{{ route('usuario.index') }}" class="sidebar-link">
                                        <i class="ti ti-user"></i>
                                        <span class="hide-menu">Usuarios</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('tiporequerimiento.index') }}" class="sidebar-link">
                                        <i class="ti ti-checklist"></i>
                                        <span class="hide-menu">Tipos de requerimientos</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('sistema.index') }}" class="sidebar-link">
                                        <i class="ti ti-screen-share"></i>
                                        <span class="hide-menu">Sistemas</span>
                                    </a>
                                </li>
                                @endif

                                @if(in_array(Auth::user()->rol, ['Administrador TI', 'Titular de área', 'Capturista']))
                                <li class="sidebar-item">
                                    <a href="{{ route('solicitante.index') }}" class="sidebar-link">
                                        <i class="ti ti-file-pencil"></i>
                                        <span class="hide-menu">Solicitantes</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <iconify-icon icon="solar:file-text-linear" class="aside-icon"></iconify-icon>
                                <span class="hide-menu">Oficios</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @if(in_array(Auth::user()->rol, ['Administrador TI', 'Capturista']))
                                <li class="sidebar-item">
                                    <a href="{{ route('oficio.index') }}" class="sidebar-link">
                                        <i class="ti ti-file-plus"></i>
                                        <span class="hide-menu">Registro</span>
                                    </a>
                                </li>
                                @endif

                                @if(in_array(Auth::user()->rol, ['Administrador TI', 'Titular de área']))
                                <li class="sidebar-item">
                                    <a href="{{ route('turno.index') }}" class="sidebar-link">
                                        <i class="ti ti-tournament"></i>
                                        <span class="hide-menu">Turno</span>
                                    </a>
                                </li>
                                @endif

                                @if(in_array(Auth::user()->rol, ['Administrador TI', 'Responsable']))
                                <li class="sidebar-item">
                                    <a href="{{ route('seguimiento.index') }}" class="sidebar-link">
                                        <i class="ti ti-arrow-guide"></i>
                                        <span class="hide-menu">Seguimiento</span>
                                    </a>
                                </li>
                                @endif

                                @if(in_array(Auth::user()->rol, ['Administrador TI', 'Capturista']))
                                <li class="sidebar-item">
                                    <a href="{{ route('respuestas.index') }}" class="sidebar-link">
                                        <i class="ti ti-file-symlink"></i>
                                        <span class="hide-menu">Respuesta</span>
                                    </a>
                                </li>
                                @endif

                                <li class="sidebar-item">
                                    <a href="{{ route('buscador.index') }}" class="sidebar-link">
                                        <i class="ti ti-file-search"></i>
                                        <span class="hide-menu">Buscador</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @if(in_array(Auth::user()->rol, ['Administrador TI', 'Responsable']))
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('actividad.index') }}" aria-expanded="false">
                                <iconify-icon icon="solar:file-check-linear" class="aside-icon"></iconify-icon>
                                <span class="hide-menu">Actividades</span>
                            </a>
                        </li>
                        @endif

                        @if(in_array(Auth::user()->rol, ['Administrador TI', 'Analista', 'Titular de área', 'Responsable']))
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <iconify-icon icon="solar:diagram-up-broken" class="aside-icon"></iconify-icon>
                                <span class="hide-menu">Informes</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ route('informes.oficios') }}" class="sidebar-link">
                                        <i class="ti ti-file-analytics"></i>
                                        <span class="hide-menu">Oficios</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('informes.actividades') }}" class="sidebar-link">
                                        <i class="ti ti-home-stats"></i>
                                        <span class="hide-menu">Actividades</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                    </ul>
                </nav>
            </div>
        </aside>

        <div class="page-wrapper">
            <header class="topbar rounded-0 border-0 bg-morena-primary">
                <div class="app-header border-0 shadow-none">
                    <nav class="navbar navbar-expand-xl container-fluid p-0 header-container" style="font-family: inherit;">
                        
                        <div class="d-flex align-items-center justify-content-between w-100" style="height: 64px;">

                            <div class="d-flex align-items-center">
                                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                                    <i class="ti ti-menu-2"></i>
                                </a>

                                <a class="text-nowrap logo-img d-flex align-items-center">
                                    <img src="{{ asset('materialpro/assets/images/morenaicons/Logolayoyt.png') }}"
                                        alt="logo" class="logo-custom" style="height: 40px;" />
                                </a>

                                <span class="text-white app-title-text ms-3">
                                    SISTEMA DE OFICIOS
                                </span>

                                <a href="/" class="text-white d-flex align-items-center ms-3 d-none d-sm-flex" title="Dashboard">
                                    <iconify-icon icon="solar:home-2-linear" width="22" height="22"></iconify-icon>
                                </a>
                            </div>

                            <div class="d-flex align-items-center justify-content-end ms-auto">
                                
                                <span class="d-none d-lg-block text-white user-name-text text-uppercase text-end  me-3" style="max-width: 250px;">
                                    {{ Auth::user()->nombre ?? 'USUARIO' }}
                                </span>

                                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5"
                                    style="width: 40px; height: 40px; min-width: 40px; flex-shrink: 0; line-height: 0;">
                                    {{ substr(Auth::user()->nombre ?? 'U', 0, 1) }}
                                </div>

                                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 ms-3 d-flex align-items-center">
                                    @csrf
                                    <button type="submit" class="btn p-0 m-0 border-0 text-white shadow-none opacity-75 nav-icon-hover" title="Cerrar Sesión" style="background: transparent;">
                                        <i class="ti ti-logout" style="font-size: 1.6rem;"></i>
                                    </button>
                                </form>

                            </div>

                        </div>
                    </nav>
                </div>
            </header>

            <aside class="left-sidebar with-horizontal">
                <div>
                    <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
                        <ul id="sidebarnav">

                            @if(in_array(Auth::user()->rol, ['Administrador TI', 'Titular de área', 'Capturista']))
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:user-id-line-duotone" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Administración</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    @if(in_array(Auth::user()->rol, ['Administrador TI', 'Titular de área']))
                                    <li class="sidebar-item">
                                        <a href="{{ route('usuario.index') }}" class="sidebar-link">
                                            <i class="ti ti-user"></i>
                                            <span class="hide-menu">Usuarios</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('tiporequerimiento.index') }}" class="sidebar-link">
                                            <i class="ti ti-checklist"></i>
                                            <span class="hide-menu">Tipos de requerimientos</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('sistema.index') }}" class="sidebar-link">
                                            <i class="ti ti-screen-share"></i>
                                            <span class="hide-menu">Sistemas</span>
                                        </a>
                                    </li>
                                    @endif

                                    @if(in_array(Auth::user()->rol, ['Administrador TI', 'Titular de área', 'Capturista']))
                                    <li class="sidebar-item">
                                        <a href="{{ route('solicitante.index') }}" class="sidebar-link">
                                            <i class="ti ti-file-pencil"></i>
                                            <span class="hide-menu">Solicitantes</span>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:file-text-linear" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Oficios</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    @if(in_array(Auth::user()->rol, ['Administrador TI', 'Capturista']))
                                    <li class="sidebar-item">
                                        <a href="{{ route('oficio.index') }}" class="sidebar-link">
                                            <i class="ti ti-file-plus"></i>
                                            <span class="hide-menu">Registro</span>
                                        </a>
                                    </li>
                                    @endif

                                    @if(in_array(Auth::user()->rol, ['Administrador TI', 'Titular de área']))
                                    <li class="sidebar-item">
                                        <a href="{{ route('turno.index') }}" class="sidebar-link">
                                            <i class="ti ti-tournament"></i>
                                            <span class="hide-menu">Turno</span>
                                        </a>
                                    </li>
                                    @endif

                                    @if(in_array(Auth::user()->rol, ['Administrador TI', 'Responsable']))
                                    <li class="sidebar-item">
                                        <a href="{{ route('seguimiento.index') }}" class="sidebar-link">
                                            <i class="ti ti-arrow-guide"></i>
                                            <span class="hide-menu">Seguimiento</span>
                                        </a>
                                    </li>
                                    @endif

                                    @if(in_array(Auth::user()->rol, ['Administrador TI', 'Capturista']))
                                    <li class="sidebar-item">
                                        <a href="{{ route('respuestas.index') }}" class="sidebar-link">
                                            <i class="ti ti-file-symlink"></i>
                                            <span class="hide-menu">Respuesta</span>
                                        </a>
                                    </li>
                                    @endif

                                    <li class="sidebar-item">
                                        <a href="{{ route('buscador.index') }}" class="sidebar-link">
                                            <i class="ti ti-file-search"></i>
                                            <span class="hide-menu">Buscador</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        @if(in_array(Auth::user()->rol, ['Administrador TI', 'Responsable']))
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('actividad.index') }}" aria-expanded="false">
                                <iconify-icon icon="solar:file-check-linear" class="aside-icon"></iconify-icon>
                                <span class="hide-menu">Actividades</span>
                            </a>
                        </li>
                        @endif

                            @if(in_array(Auth::user()->rol, ['Administrador TI', 'Analista', 'Titular de área', 'Responsable']))
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:diagram-up-broken" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Informes</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="{{ route('informes.oficios') }}" class="sidebar-link">
                                            <i class="ti ti-file-analytics"></i>
                                            <span class="hide-menu">Oficios</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('informes.actividades') }}" class="sidebar-link">
                                            <i class="ti ti-home-stats"></i>
                                            <span class="hide-menu">Actividades</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                        </ul>
                    </nav>
                </div>
            </aside>
            
            <div class="body-wrapper pt-4">
                <div class="container-fluid mt-3">

                    <div class="mb-3">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center"
                                role="alert"
                                style="background-color: #d1e7dd; color: #0f5132; border-left: 5px solid #198754 !important;">
                                <i class="ti ti-circle-check fs-4 me-2 text-success"></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center"
                                role="alert"
                                style="background-color: #f8d7da; color: #842029; border-left: 5px solid #dc3545 !important;">
                                
                                <div>{{ session('error') }}</div>
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
                }, 5000); 
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var mobileMenuLinks = document.querySelectorAll('.left-sidebar.with-vertical .sidebar-link.has-arrow');

            mobileMenuLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    var nextUl = this.nextElementSibling;

                    if (nextUl && nextUl.tagName === 'UL') {
                        var isOpen = nextUl.style.display === 'block' || nextUl.classList.contains('show');

                        if (isOpen) {
                            nextUl.style.display = 'none';
                            nextUl.classList.remove('in', 'show');
                            this.classList.remove('active');
                            this.setAttribute('aria-expanded', 'false');
                        } else {
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
        function convertirSelectABuscador(idSelect) {
            const originalSelect = document.getElementById(idSelect);
            if (!originalSelect) return;

            const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
            if (wrapperPrevio) {
                wrapperPrevio.remove();
                originalSelect.style.display = 'block'; 
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'searchable-dropdown-wrapper';

            const trigger = document.createElement('button');
            trigger.className = 'form-select searchable-trigger';
            trigger.type = 'button'; 
            const selectedOption = originalSelect.options[originalSelect.selectedIndex];
            trigger.textContent = selectedOption ? selectedOption.text : 'Seleccione una opción';

            const menu = document.createElement('div');
            menu.className = 'searchable-menu';

            const inputSearch = document.createElement('input');
            inputSearch.className = 'form-control mb-2';
            inputSearch.type = 'text';
            inputSearch.placeholder = 'Buscar...';

            const optionsList = document.createElement('div');
            optionsList.className = 'searchable-options';

            function poblarOpciones() {
                optionsList.innerHTML = ''; 
                Array.from(originalSelect.options).forEach(option => {
                    if (option.value === "") return;

                    const item = document.createElement('div');
                    item.className = 'searchable-option';
                    item.textContent = option.text;
                    item.dataset.value = option.value;

                    item.addEventListener('click', () => {
                        originalSelect.value = option.value;
                        originalSelect.dispatchEvent(new Event('change'));
                        trigger.textContent = option.text;
                        menu.classList.remove('show');
                        inputSearch.value = ''; 
                        filtrarOpciones(''); 
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

            inputSearch.addEventListener('keyup', (e) => {
                filtrarOpciones(e.target.value);
            });

            trigger.addEventListener('click', (e) => {
                document.querySelectorAll('.searchable-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('show');
                });
                menu.classList.toggle('show');
                if (menu.classList.contains('show')) inputSearch.focus();
            });

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) {
                    menu.classList.remove('show');
                }
            });

            menu.appendChild(inputSearch);
            menu.appendChild(optionsList);
            wrapper.appendChild(trigger);
            wrapper.appendChild(menu);

            originalSelect.parentNode.insertBefore(wrapper, originalSelect.nextSibling);
            originalSelect.style.display = 'none';
        }
    </script>
</body>

</html>